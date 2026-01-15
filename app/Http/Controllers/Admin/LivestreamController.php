<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Livestream;
use App\Models\Game;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LivestreamController extends Controller
{
    public function index(Request $request)
    {
        $query = Livestream::with(['creator', 'game', 'tournament']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $livestreams = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Livestream::count(),
            'live' => Livestream::where('status', 'live')->count(),
            'scheduled' => Livestream::where('status', 'scheduled')->count(),
            'ended' => Livestream::where('status', 'ended')->count(),
        ];

        return view('admin.livestreams.index', compact('livestreams', 'stats'));
    }

    public function create()
    {
        $games = Game::where('status', 'active')->orderBy('name')->get();
        $tournaments = Tournament::where('status', 'ongoing')->orderBy('name')->get();
        
        return view('admin.livestreams.create', compact('games', 'tournaments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'stream_url' => 'required|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'game_id' => 'nullable|exists:games,id',
            'tournament_id' => 'nullable|exists:tournaments,id',
            'status' => 'required|in:scheduled,live,ended',
            'scheduled_at' => 'nullable|date',
            'is_featured' => 'boolean',
        ]);

        // Convert URL to embed
        $embedData = Livestream::convertToEmbedUrl($validated['stream_url']);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'embed_url' => $embedData['embed_url'],
            'platform' => $embedData['platform'],
            'status' => $validated['status'],
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'game_id' => $validated['game_id'] ?? null,
            'tournament_id' => $validated['tournament_id'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'created_by' => Auth::id(),
        ];

        if ($validated['status'] === 'live') {
            $data['started_at'] = now();
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('livestreams', 'public');
        }

        Livestream::create($data);

        return redirect()->route('admin.livestreams.index')
            ->with('success', 'Đã tạo livestream thành công!');
    }

    public function edit(Livestream $livestream)
    {
        $games = Game::where('status', 'active')->orderBy('name')->get();
        $tournaments = Tournament::orderBy('name')->get();
        
        return view('admin.livestreams.edit', compact('livestream', 'games', 'tournaments'));
    }

    public function update(Request $request, Livestream $livestream)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'stream_url' => 'required|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'game_id' => 'nullable|exists:games,id',
            'tournament_id' => 'nullable|exists:tournaments,id',
            'status' => 'required|in:scheduled,live,ended',
            'scheduled_at' => 'nullable|date',
            'is_featured' => 'boolean',
        ]);

        $embedData = Livestream::convertToEmbedUrl($validated['stream_url']);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'embed_url' => $embedData['embed_url'],
            'platform' => $embedData['platform'],
            'status' => $validated['status'],
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'game_id' => $validated['game_id'] ?? null,
            'tournament_id' => $validated['tournament_id'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
        ];

        // Update timestamps based on status change
        if ($validated['status'] === 'live' && !$livestream->started_at) {
            $data['started_at'] = now();
        }
        if ($validated['status'] === 'ended' && !$livestream->ended_at) {
            $data['ended_at'] = now();
        }

        if ($request->hasFile('thumbnail')) {
            if ($livestream->thumbnail) {
                Storage::disk('public')->delete($livestream->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('livestreams', 'public');
        }

        $livestream->update($data);

        return redirect()->route('admin.livestreams.index')
            ->with('success', 'Đã cập nhật livestream thành công!');
    }

    public function destroy(Livestream $livestream)
    {
        if ($livestream->thumbnail) {
            Storage::disk('public')->delete($livestream->thumbnail);
        }
        
        $livestream->delete();

        return redirect()->route('admin.livestreams.index')
            ->with('success', 'Đã xóa livestream thành công!');
    }

    public function toggleStatus(Livestream $livestream, string $status)
    {
        if (!in_array($status, ['scheduled', 'live', 'ended'])) {
            return back()->with('error', 'Trạng thái không hợp lệ.');
        }

        $data = ['status' => $status];

        if ($status === 'live' && !$livestream->started_at) {
            $data['started_at'] = now();
        }
        if ($status === 'ended' && !$livestream->ended_at) {
            $data['ended_at'] = now();
        }

        $livestream->update($data);

        return back()->with('success', 'Đã cập nhật trạng thái livestream.');
    }
}
