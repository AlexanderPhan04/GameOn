<?php

namespace App\Http\Controllers;

use App\Models\Livestream;
use Illuminate\Http\Request;

class LivestreamController extends Controller
{
    /**
     * Danh sách livestream cho user
     */
    public function index()
    {
        $liveNow = Livestream::active()
            ->live()
            ->with(['game', 'creator'])
            ->latest('started_at')
            ->get();

        $upcoming = Livestream::active()
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>', now())
            ->with(['game', 'creator'])
            ->orderBy('scheduled_at')
            ->take(6)
            ->get();

        $recent = Livestream::active()
            ->where('status', 'ended')
            ->with(['game', 'creator'])
            ->latest('ended_at')
            ->take(8)
            ->get();

        $featured = Livestream::active()
            ->featured()
            ->with(['game', 'creator'])
            ->latest()
            ->first();

        return view('livestreams.index', compact('liveNow', 'upcoming', 'recent', 'featured'));
    }

    /**
     * Xem chi tiết livestream
     */
    public function show(Livestream $livestream)
    {
        if (!$livestream->is_active) {
            abort(404);
        }

        $livestream->load(['game', 'tournament', 'creator']);
        $livestream->incrementViewCount();

        // Lấy các livestream liên quan
        $related = Livestream::active()
            ->where('id', '!=', $livestream->id)
            ->when($livestream->game_id, function ($q) use ($livestream) {
                $q->where('game_id', $livestream->game_id);
            })
            ->latest()
            ->take(4)
            ->get();

        return view('livestreams.show', compact('livestream', 'related'));
    }
}
