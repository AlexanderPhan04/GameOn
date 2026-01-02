<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminInvitationMail;
use App\Models\AdminInvitation;
use App\Models\AdminPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminInvitationController extends Controller
{
    /**
     * Chỉ Super Admin mới có quyền
     */
    private function checkSuperAdminAccess()
    {
        if (!Auth::check() || Auth::user()->user_role !== 'super_admin') {
            abort(403, 'Chỉ Super Admin mới có quyền thực hiện chức năng này');
        }
    }

    /**
     * Hiển thị danh sách Admin và lời mời
     */
    public function index()
    {
        $this->checkSuperAdminAccess();

        // Danh sách Admin hiện tại với permissions
        $admins = User::whereIn('user_role', ['admin', 'super_admin'])
            ->with('adminPermission')
            ->orderByRaw("FIELD(user_role, 'super_admin', 'admin')")
            ->orderBy('created_at', 'desc')
            ->get();

        // Đếm số Super Admin
        $superAdminCount = User::where('user_role', 'super_admin')->count();

        // Danh sách lời mời đang chờ
        $pendingInvitations = AdminInvitation::with('inviter')
            ->pending()
            ->orderBy('created_at', 'desc')
            ->get();

        // Danh sách quyền có thể cấp
        $availablePermissions = AdminPermission::AVAILABLE_PERMISSIONS;

        return view('admin.admins.index', compact('admins', 'pendingInvitations', 'availablePermissions', 'superAdminCount'));
    }

    /**
     * Hiển thị form mời Admin mới
     */
    public function create()
    {
        $this->checkSuperAdminAccess();

        $availablePermissions = AdminPermission::AVAILABLE_PERMISSIONS;

        return view('admin.admins.invite', compact('availablePermissions'));
    }

    /**
     * Gửi lời mời Admin
     */
    public function store(Request $request)
    {
        $this->checkSuperAdminAccess();

        $request->validate([
            'email' => 'required|email',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string|in:' . implode(',', array_keys(AdminPermission::AVAILABLE_PERMISSIONS)),
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'permissions.required' => 'Vui lòng chọn ít nhất 1 quyền',
            'permissions.min' => 'Vui lòng chọn ít nhất 1 quyền',
        ]);

        $email = $request->email;

        // Kiểm tra user đã tồn tại và đã là admin chưa
        $existingUser = User::where('email', $email)->first();
        if ($existingUser && $existingUser->user_role === 'admin') {
            return back()->withInput()->with('error', "Người dùng {$email} đã là Admin rồi!");
        }

        if ($existingUser && $existingUser->user_role === 'super_admin') {
            return back()->withInput()->with('error', "Không thể mời Super Admin!");
        }

        // Kiểm tra đã có lời mời pending chưa
        $existingInvitation = AdminInvitation::where('email', $email)
            ->valid()
            ->first();

        if ($existingInvitation) {
            return back()->withInput()->with('error', "Đã có lời mời đang chờ xử lý cho email này!");
        }

        try {
            // Tạo lời mời
            $invitation = AdminInvitation::create([
                'email' => $email,
                'token' => AdminInvitation::generateToken(),
                'invited_by' => Auth::id(),
                'permissions' => $request->permissions,
                'status' => 'pending',
                'expires_at' => now()->addDays(7), // Hết hạn sau 7 ngày
            ]);

            // Gửi email
            Mail::to($email)->send(new AdminInvitationMail($invitation));

            return redirect()->route('admin.admins.index')
                ->with('success', "Đã gửi lời mời Admin tới {$email}!");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Có lỗi xảy ra khi gửi lời mời: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý chấp nhận lời mời (public route)
     */
    public function accept(string $token)
    {
        $invitation = AdminInvitation::where('token', $token)->first();

        if (!$invitation) {
            return view('admin.admins.invitation-result', [
                'success' => false,
                'message' => 'Lời mời không tồn tại hoặc đã bị xóa.',
            ]);
        }

        if ($invitation->status === 'accepted') {
            return view('admin.admins.invitation-result', [
                'success' => false,
                'message' => 'Lời mời này đã được chấp nhận trước đó.',
            ]);
        }

        if ($invitation->status === 'rejected') {
            return view('admin.admins.invitation-result', [
                'success' => false,
                'message' => 'Lời mời này đã bị từ chối.',
            ]);
        }

        if ($invitation->isExpired()) {
            $invitation->markAsExpired();
            return view('admin.admins.invitation-result', [
                'success' => false,
                'message' => 'Lời mời này đã hết hạn.',
            ]);
        }

        try {
            DB::beginTransaction();

            // Kiểm tra user đã tồn tại chưa
            $user = User::where('email', $invitation->email)->first();

            if ($user) {
                // User đã tồn tại - cập nhật role
                $user->update(['user_role' => 'admin']);
            } else {
                // Tạo user mới
                $tempPassword = Str::random(12);
                $user = User::create([
                    'name' => explode('@', $invitation->email)[0],
                    'email' => $invitation->email,
                    'password' => Hash::make($tempPassword),
                    'user_role' => 'admin',
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]);

                // TODO: Gửi email thông báo mật khẩu tạm thời
                // Hoặc redirect đến trang đặt mật khẩu
            }

            // Tạo/Cập nhật permissions
            AdminPermission::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'permissions' => $invitation->permissions,
                    'granted_by' => $invitation->invited_by,
                ]
            );

            // Đánh dấu invitation đã được chấp nhận
            $invitation->markAsAccepted();

            DB::commit();

            // Nếu là user mới, chuyển đến trang đặt mật khẩu
            if (isset($tempPassword)) {
                return view('admin.admins.invitation-result', [
                    'success' => true,
                    'message' => 'Chúc mừng! Bạn đã trở thành Admin.',
                    'isNewUser' => true,
                    'tempPassword' => $tempPassword,
                    'email' => $user->email,
                ]);
            }

            return view('admin.admins.invitation-result', [
                'success' => true,
                'message' => 'Chúc mừng! Bạn đã trở thành Admin. Vui lòng đăng nhập để truy cập trang quản trị.',
                'isNewUser' => false,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return view('admin.admins.invitation-result', [
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Xử lý từ chối lời mời (public route)
     */
    public function reject(string $token)
    {
        $invitation = AdminInvitation::where('token', $token)->first();

        if (!$invitation) {
            return view('admin.admins.invitation-result', [
                'success' => false,
                'message' => 'Lời mời không tồn tại.',
            ]);
        }

        if ($invitation->status !== 'pending') {
            return view('admin.admins.invitation-result', [
                'success' => false,
                'message' => 'Lời mời này đã được xử lý trước đó.',
            ]);
        }

        $invitation->markAsRejected();

        return view('admin.admins.invitation-result', [
            'success' => true,
            'message' => 'Bạn đã từ chối lời mời Admin.',
        ]);
    }

    /**
     * Hủy lời mời đang chờ
     */
    public function cancelInvitation(AdminInvitation $invitation)
    {
        $this->checkSuperAdminAccess();

        if ($invitation->status !== 'pending') {
            return back()->with('error', 'Không thể hủy lời mời đã được xử lý.');
        }

        $invitation->delete();

        return back()->with('success', 'Đã hủy lời mời.');
    }

    /**
     * Gửi lại lời mời
     */
    public function resendInvitation(AdminInvitation $invitation)
    {
        $this->checkSuperAdminAccess();

        if ($invitation->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể gửi lại lời mời đang chờ xử lý.');
        }

        // Cập nhật token và thời hạn mới
        $invitation->update([
            'token' => AdminInvitation::generateToken(),
            'expires_at' => now()->addDays(7),
        ]);

        // Gửi email
        Mail::to($invitation->email)->send(new AdminInvitationMail($invitation));

        return back()->with('success', "Đã gửi lại lời mời tới {$invitation->email}!");
    }

    /**
     * Hiển thị form chỉnh sửa quyền Admin
     */
    public function editPermissions(User $user)
    {
        $this->checkSuperAdminAccess();

        if ($user->user_role !== 'admin') {
            return back()->with('error', 'Người dùng này không phải Admin.');
        }

        $adminPermission = $user->adminPermission ?? new AdminPermission(['permissions' => []]);
        $availablePermissions = AdminPermission::AVAILABLE_PERMISSIONS;

        return view('admin.admins.edit-permissions', compact('user', 'adminPermission', 'availablePermissions'));
    }

    /**
     * Cập nhật quyền Admin
     */
    public function updatePermissions(Request $request, User $user)
    {
        $this->checkSuperAdminAccess();

        if ($user->user_role !== 'admin') {
            return back()->with('error', 'Người dùng này không phải Admin.');
        }

        $request->validate([
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string|in:' . implode(',', array_keys(AdminPermission::AVAILABLE_PERMISSIONS)),
        ]);

        AdminPermission::updateOrCreate(
            ['user_id' => $user->id],
            [
                'permissions' => $request->permissions,
                'granted_by' => Auth::id(),
            ]
        );

        return redirect()->route('admin.admins.index')
            ->with('success', "Đã cập nhật quyền cho Admin {$user->name}!");
    }

    /**
     * Thu hồi quyền Admin (chuyển về participant)
     */
    public function revokeAdmin(User $user)
    {
        $this->checkSuperAdminAccess();

        if ($user->user_role !== 'admin') {
            return back()->with('error', 'Người dùng này không phải Admin.');
        }

        try {
            DB::beginTransaction();

            // Xóa permissions
            AdminPermission::where('user_id', $user->id)->delete();

            // Chuyển role về participant
            $user->update(['user_role' => 'participant']);

            DB::commit();

            return back()->with('success', "Đã thu hồi quyền Admin của {$user->name}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
