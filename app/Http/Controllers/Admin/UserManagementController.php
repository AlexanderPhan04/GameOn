<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class UserManagementController extends Controller
{
    /**
     * Check if user has admin access
     */
    private function checkAdminAccess()
    {
        if (! Auth::check() || ! in_array(Auth::user()->user_role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized access');
        }
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();

        $query = User::query();

        // Current user restrictions
        $currentUser = Auth::user();
        if ($currentUser->user_role !== 'super_admin') {
            // Admin không thể thấy Super Admin
            $query->where('user_role', '!=', 'super_admin');
        }

        // Apply filters
        $query->search($request->search)
            ->withRole($request->role)
            ->withStatus($request->status);

        // Apply sorting
        $sortField = $request->sort ?? 'created_at';
        $sortDirection = $request->direction ?? 'desc';

        $allowedSorts = ['created_at', 'last_login', 'name', 'email'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Get paginated results
        $perPage = $request->per_page ?? 25;
        $users = $query->paginate($perPage);

        // Keep query parameters in pagination links
        $users->appends($request->all());

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user profile
     */
    public function show(User $user)
    {
        $this->checkAdminAccess();
        $currentUser = Auth::user();

        // Check if admin is trying to view super admin or other admins
        if ($currentUser->user_role !== 'super_admin') {
            if ($user->user_role === 'super_admin' || ($user->user_role === 'admin' && $user->id !== $currentUser->id)) {
                abort(403, 'Unauthorized access');
            }
        }

        // Load relationships
        $user->load(['profile', 'activity']);

        // If request asks for partial (offcanvas) or is AJAX, return fragment only
        if (request()->ajax() || request()->boolean('partial')) {
            return view('admin.users.partials.detail', compact('user'));
        }

        // Otherwise, redirect back to index (we no longer serve a standalone details page)
        return redirect()->route('admin.users.index');
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $this->checkAdminAccess();
        $currentUser = Auth::user();

        // Admin/Super Admin không thể edit chính mình
        if ($user->id === $currentUser->id) {
            return redirect()->route('admin.users.show', $user)
                ->with('error', 'Bạn không thể chỉnh sửa tài khoản của chính mình');
        }

        // Admin không thể edit Super Admin hoặc Admin khác
        if ($currentUser->user_role !== 'super_admin') {
            if ($user->user_role === 'super_admin' || $user->user_role === 'admin') {
                abort(403, 'Unauthorized access');
            }
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $this->checkAdminAccess();
        $currentUser = Auth::user();

        // Admin/Super Admin không thể update chính mình
        if ($user->id === $currentUser->id) {
            return redirect()->route('admin.users.show', $user)
                ->with('error', 'Bạn không thể cập nhật tài khoản của chính mình');
        }

        // Admin không thể update Super Admin hoặc Admin khác
        if ($currentUser->user_role !== 'super_admin') {
            if ($user->user_role === 'super_admin' || $user->user_role === 'admin') {
                abort(403, 'Unauthorized access');
            }
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'full_name' => 'nullable|string|max:255',
            'user_role' => 'required|in:super_admin,admin,participant',
            'status' => 'required|in:active,suspended,banned,deleted',
            'bio' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        // Admin cannot set super_admin role
        if ($currentUser->user_role !== 'super_admin' && $validated['user_role'] === 'super_admin') {
            return back()->withErrors(['user_role' => 'Không có quyền thiết lập Super Admin']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Cập nhật thông tin người dùng thành công!');
    }

    /**
     * Update user status
     */
    public function updateStatus(Request $request, User $user)
    {
        $this->checkAdminAccess();
        $currentUser = Auth::user();

        // Admin/Super Admin không thể thay đổi status của chính mình
        if ($user->id === $currentUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không thể thay đổi trạng thái của chính mình',
            ], 403);
        }

        // Admin không thể thay đổi status của Super Admin hoặc Admin khác
        if ($currentUser->user_role !== 'super_admin') {
            if (in_array($user->user_role, ['super_admin', 'admin'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền thay đổi trạng thái của người dùng này',
                ], 403);
            }
        }
        $validated = $request->validate([
            'status' => 'required|in:active,suspended,banned,deleted',
            'suspended_from' => 'nullable|date',
            'suspended_until' => 'nullable|date|after_or_equal:suspended_from',
        ]);

        $updateData = ['status' => $validated['status']];
        
        // Handle suspend dates
        if ($validated['status'] === 'suspended') {
            $updateData['suspended_from'] = $validated['suspended_from'] ?? now();
            $updateData['suspended_until'] = $validated['suspended_until'] ?? null;
        } else {
            // Clear suspend dates when status is not suspended
            $updateData['suspended_from'] = null;
            $updateData['suspended_until'] = null;
        }

        $user->update($updateData);

        // Broadcast event to notify user in realtime
        event(new \App\Events\UserStatusChanged($user));

        // Get status display name
        $statusNames = [
            'active' => __('app.users.active'),
            'suspended' => __('app.users.suspended'),
            'banned' => __('app.users.banned'),
            'deleted' => __('app.users.deleted'),
        ];

        // Get badge class
        $badgeClasses = [
            'active' => 'bg-success',
            'suspended' => 'bg-warning',
            'banned' => 'bg-danger',
            'deleted' => 'bg-secondary',
        ];

        $statusName = $statusNames[$validated['status']];
        $badgeClass = $badgeClasses[$validated['status']];

        return response()->json([
            'success' => true,
            'message' => __('app.users.status_updated_successfully', ['status' => $statusName]),
            'status' => $user->status,
            'status_display' => $statusName,
            'badge_class' => $badgeClass,
        ]);
    }

    /**
     * Bulk update users
     */
    public function bulkUpdate(Request $request)
    {
        $this->checkAdminAccess();
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'action' => 'required|in:activate,suspend,ban,delete',
        ]);

        $currentUser = Auth::user();
        $userIds = $validated['user_ids'];

        // Remove current user from the list (không thể bulk update chính mình)
        $userIds = array_diff($userIds, [$currentUser->id]);

        // Remove restricted users from the list based on current user role
        if ($currentUser->user_role !== 'super_admin') {
            $restrictedIds = User::whereIn('id', $userIds)
                ->where(function ($query) {
                    $query->where('user_role', 'super_admin')
                        ->orWhere('user_role', 'admin');
                })
                ->pluck('id')
                ->toArray();
            $userIds = array_diff($userIds, $restrictedIds);
        }
        if (empty($userIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Không có người dùng nào được cập nhật',
            ]);
        }

        $status = match ($validated['action']) {
            'activate' => 'active',
            'suspend' => 'suspended',
            'ban' => 'banned',
            'delete' => 'deleted',
        };

        User::whereIn('id', $userIds)->update(['status' => $status]);

        // Broadcast event to each affected user
        $affectedUsers = User::whereIn('id', $userIds)->get();
        foreach ($affectedUsers as $user) {
            event(new \App\Events\UserStatusChanged($user));
        }

        return response()->json([
            'success' => true,
            'message' => "Đã cập nhật " . count($userIds) . " người dùng",
        ]);
    }

    /**
     * Export users to CSV
     */
    public function export(Request $request)
    {
        $this->checkAdminAccess();
        $query = User::query();

        $currentUser = Auth::user();
        if ($currentUser->user_role !== 'super_admin') {
            $query->where('user_role', '!=', 'super_admin');
        }

        // Apply same filters as index
        $query->search($request->search)
            ->withRole($request->role)
            ->withStatus($request->status ?? 'active');

        $users = $query->get();

        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            // CSV header
            fputcsv($file, [
                'ID',
                'Username',
                'Email',
                'Full Name',
                'Role',
                'Status',
                'Created At',
                'Last Login',
                'Country',
                'Phone',
            ]);

            // CSV data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->full_name,
                    $user->role_display_name,
                    $user->status_display_name,
                    $user->created_at?->format('Y-m-d H:i:s'),
                    $user->last_login?->format('Y-m-d H:i:s'),
                    $user->country,
                    $user->phone,
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
