<?php

namespace App\Http\Controllers;

use App\Models\GamerVerificationRequest;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GamerVerificationController extends Controller
{
    /**
     * Hiển thị form đăng ký Pro Gamer
     */
    public function create()
    {
        $user = Auth::user();

        // Kiểm tra đã là verified gamer chưa
        if ($user->is_verified_gamer) {
            return redirect()->route('profile.show')
                ->with('info', 'Bạn đã được xác minh là Pro Gamer.');
        }

        // Kiểm tra có yêu cầu đang chờ duyệt không
        $pendingRequest = GamerVerificationRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($pendingRequest) {
            return view('verification.pending', compact('pendingRequest'));
        }

        // Lấy yêu cầu gần nhất (nếu bị từ chối)
        $lastRequest = GamerVerificationRequest::where('user_id', $user->id)
            ->latest()
            ->first();

        // Lấy danh sách games (dùng status = 'active' thay vì is_active)
        $games = Game::where('status', 'active')->orderBy('name')->get();

        return view('verification.create', compact('games', 'lastRequest'));
    }

    /**
     * Gửi yêu cầu xác minh
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Kiểm tra đã là verified gamer chưa
        if ($user->is_verified_gamer) {
            return redirect()->route('profile.show')
                ->with('info', 'Bạn đã được xác minh là Pro Gamer.');
        }

        // Kiểm tra có yêu cầu đang chờ duyệt không
        $hasPending = GamerVerificationRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return back()->with('error', 'Bạn đã có yêu cầu đang chờ duyệt.');
        }

        $validated = $request->validate([
            'game_name' => 'required|string|max:255',
            'in_game_name' => 'required|string|max:255',
            'in_game_id' => 'nullable|string|max:255',
            'rank_tier' => 'nullable|string|max:255',
            'achievements' => 'nullable|string|max:2000',
            'proof_links' => 'nullable|string|max:1000',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'additional_info' => 'nullable|string|max:1000',
        ]);

        // Upload ảnh chứng minh
        if ($request->hasFile('proof_image')) {
            $validated['proof_image'] = $request->file('proof_image')
                ->store('verification-proofs', 'public');
        }

        $validated['user_id'] = $user->id;
        $validated['status'] = 'pending';

        GamerVerificationRequest::create($validated);

        return redirect()->route('verification.status')
            ->with('success', 'Yêu cầu xác minh Pro Gamer đã được gửi thành công!');
    }

    /**
     * Xem trạng thái yêu cầu
     */
    public function status()
    {
        $user = Auth::user();

        $requests = GamerVerificationRequest::where('user_id', $user->id)
            ->with('reviewer')
            ->latest()
            ->get();

        return view('verification.status', compact('requests'));
    }
}
