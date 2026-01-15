<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GamerVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GamerVerificationController extends Controller
{
    /**
     * Danh sách yêu cầu xác minh
     */
    public function index(Request $request)
    {
        $query = GamerVerificationRequest::with(['user.profile', 'reviewer']);

        // Lọc theo trạng thái
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Tìm kiếm
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('game_name', 'like', "%{$search}%")
                    ->orWhere('in_game_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Sắp xếp: pending lên đầu, sau đó theo thời gian
        $query->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc');

        $requests = $query->paginate(20)->withQueryString();

        // Thống kê
        $stats = [
            'total' => GamerVerificationRequest::count(),
            'pending' => GamerVerificationRequest::where('status', 'pending')->count(),
            'approved' => GamerVerificationRequest::where('status', 'approved')->count(),
            'rejected' => GamerVerificationRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.verification.index', compact('requests', 'stats'));
    }

    /**
     * Xem chi tiết yêu cầu
     */
    public function show(GamerVerificationRequest $request)
    {
        $request->load(['user.profile', 'reviewer']);

        // Lấy lịch sử yêu cầu của user này
        $history = GamerVerificationRequest::where('user_id', $request->user_id)
            ->where('id', '!=', $request->id)
            ->with('reviewer')
            ->latest()
            ->get();

        return view('admin.verification.show', compact('request', 'history'));
    }

    /**
     * Duyệt yêu cầu
     */
    public function approve(Request $request, GamerVerificationRequest $verificationRequest)
    {
        if (!$verificationRequest->isPending()) {
            return back()->with('error', 'Yêu cầu này đã được xử lý.');
        }

        $validated = $request->validate([
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $verificationRequest->approve(Auth::id(), $validated['admin_note'] ?? null);

        return redirect()->route('admin.verification.index')
            ->with('success', 'Đã duyệt yêu cầu xác minh Pro Gamer cho ' . $verificationRequest->user->name);
    }

    /**
     * Từ chối yêu cầu
     */
    public function reject(Request $request, GamerVerificationRequest $verificationRequest)
    {
        if (!$verificationRequest->isPending()) {
            return back()->with('error', 'Yêu cầu này đã được xử lý.');
        }

        $validated = $request->validate([
            'admin_note' => 'required|string|max:1000',
        ], [
            'admin_note.required' => 'Vui lòng nhập lý do từ chối.',
        ]);

        $verificationRequest->reject(Auth::id(), $validated['admin_note']);

        return redirect()->route('admin.verification.index')
            ->with('success', 'Đã từ chối yêu cầu xác minh của ' . $verificationRequest->user->name);
    }

    /**
     * Thu hồi trạng thái Pro Gamer của user
     */
    public function revoke(Request $request, $userId)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ], [
            'reason.required' => 'Vui lòng nhập lý do thu hồi.',
        ]);

        $user = \App\Models\User::findOrFail($userId);

        if (!$user->is_verified_gamer) {
            return back()->with('error', 'Người dùng này không phải là Pro Gamer.');
        }

        // Cập nhật trạng thái user
        $user->is_verified_gamer = false;
        $user->save();

        // Cập nhật tất cả các yêu cầu đã approved thành revoked
        GamerVerificationRequest::where('user_id', $userId)
            ->where('status', 'approved')
            ->update([
                'status' => 'revoked',
                'admin_note' => $validated['reason'],
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

        return back()->with('success', 'Đã thu hồi trạng thái Pro Gamer của ' . $user->name);
    }
}
