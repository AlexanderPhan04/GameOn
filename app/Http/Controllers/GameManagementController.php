<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GameManagementController extends Controller
{
    /**
     * Apply middleware to ensure only admin/super_admin can access
     */

    /**
     * Check if user has admin privileges
     */
    private function checkAdminAccess()
    {
        if (! Auth::check() || ! in_array(Auth::user()->user_role, ['admin', 'super_admin'])) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAdminAccess();

        $games = Game::with(['creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('admin.games.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAdminAccess();

        return view('admin.games.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAdminAccess();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:games,name',
            'genre' => 'nullable|string|max:100',
            'publisher' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'status' => 'required|in:active,discontinued',
            'esport_support' => 'nullable|boolean',
            'team_size' => 'nullable|string|max:50',
            'competition_formats' => 'nullable|array',
            'game_modes' => 'nullable|array',
            'description' => 'nullable|string|max:1000',
            'official_website' => 'nullable|url|max:500',
            'logo' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ], [
            'logo.uploaded' => 'Logo không thể upload. Vui lòng chọn file nhỏ hơn 2MB.',
            'logo.image' => 'Logo phải là file hình ảnh.',
            'logo.mimes' => 'Logo phải có định dạng: jpeg, png, jpg, gif, svg.',
            'logo.max' => 'Logo không được vượt quá 2MB.',
            'banner.uploaded' => 'Banner không thể upload. Vui lòng chọn file nhỏ hơn 5MB.',
            'banner.image' => 'Banner phải là file hình ảnh.',
            'banner.mimes' => 'Banner phải có định dạng: jpeg, png, jpg, gif, svg.',
            'banner.max' => 'Banner không được vượt quá 5MB.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Convert checkbox value to boolean
        $data['is_esport_supported'] = $request->has('esport_support') ? 1 : 0;

        // Handle format_metadata
        if ($request->has('team_size') || $request->has('competition_formats') || $request->has('game_modes')) {
            $data['format_metadata'] = [
                'team_size' => $request->input('team_size'),
                'competition_formats' => $request->input('competition_formats', []),
                'game_modes' => $request->input('game_modes', []),
            ];
        }

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            if ($logoFile->isValid()) {
                $data['image_url'] = $logoFile->store('games/logos', 'public');
            }
        }

        if ($request->hasFile('banner')) {
            $bannerFile = $request->file('banner');
            if ($bannerFile->isValid()) {
                $data['banner_url'] = $bannerFile->store('games/banners', 'public');
            }
        }

        // Set created_by and updated_by
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        Game::create($data);

        return redirect()->route('admin.games.index')
            ->with('success', 'Game đã được thêm thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        $this->checkAdminAccess();

        return view('admin.games.show', compact('game'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        $this->checkAdminAccess();

        return view('admin.games.edit', compact('game'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        $this->checkAdminAccess();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:games,name,'.$game->id,
            'genre' => 'nullable|string|max:100',
            'publisher' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'status' => 'required|in:active,discontinued',
            'esport_support' => 'nullable|boolean',
            'team_size' => 'nullable|string|max:50',
            'competition_formats' => 'nullable|array',
            'game_modes' => 'nullable|array',
            'description' => 'nullable|string|max:1000',
            'official_website' => 'nullable|url|max:500',
            'logo' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ], [
            'logo.uploaded' => 'Logo không thể upload. Vui lòng chọn file nhỏ hơn 2MB.',
            'logo.image' => 'Logo phải là file hình ảnh.',
            'logo.mimes' => 'Logo phải có định dạng: jpeg, png, jpg, gif, svg.',
            'logo.max' => 'Logo không được vượt quá 2MB.',
            'banner.uploaded' => 'Banner không thể upload. Vui lòng chọn file nhỏ hơn 5MB.',
            'banner.image' => 'Banner phải là file hình ảnh.',
            'banner.mimes' => 'Banner phải có định dạng: jpeg, png, jpg, gif, svg.',
            'banner.max' => 'Banner không được vượt quá 5MB.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Convert checkbox value to boolean
        $data['is_esport_supported'] = $request->has('esport_support') ? 1 : 0;

        // Handle format_metadata
        if ($request->has('team_size') || $request->has('competition_formats') || $request->has('game_modes')) {
            $data['format_metadata'] = [
                'team_size' => $request->input('team_size'),
                'competition_formats' => $request->input('competition_formats', []),
                'game_modes' => $request->input('game_modes', []),
            ];
        }

        // Handle file uploads and delete old files
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            if ($logoFile->isValid()) {
                if ($game->image_url) {
                    Storage::disk('public')->delete($game->image_url);
                }
                $data['image_url'] = $logoFile->store('games/logos', 'public');
            }
        }

        if ($request->hasFile('banner')) {
            $bannerFile = $request->file('banner');
            if ($bannerFile->isValid()) {
                if ($game->banner_url) {
                    Storage::disk('public')->delete($game->banner_url);
                }
                $data['banner_url'] = $bannerFile->store('games/banners', 'public');
            }
        }

        // Set updated_by
        $data['updated_by'] = Auth::id();

        $game->update($data);

        return redirect()->route('admin.games.index')
            ->with('success', 'Game đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        $this->checkAdminAccess();

        // Delete associated files
        if ($game->image_url) {
            Storage::disk('public')->delete($game->image_url);
        }
        if ($game->banner_url) {
            Storage::disk('public')->delete($game->banner_url);
        }

        $game->delete();

        return response()->json([
            'success' => true,
            'message' => 'Game đã được xóa thành công!',
        ]);
    }

    /**
     * Get games data for API/AJAX
     */
    public function getGames(Request $request): JsonResponse
    {
        $games = Game::select('id', 'name', 'genre', 'is_esport_supported', 'format_metadata')
            ->when($request->esport_only, function ($query) {
                return $query->where('is_esport_supported', true);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->active()
            ->get();

        return response()->json($games);
    }
}
