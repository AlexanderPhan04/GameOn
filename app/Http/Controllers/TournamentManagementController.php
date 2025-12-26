<?php

namespace App\Http\Controllers;

use App\Models\GameManagement;
use App\Models\TournamentManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TournamentManagementController extends Controller
{
    /**
     * Check if user has admin privileges
     */
    private function checkAdminAccess()
    {
        if (! Auth::check() || ! in_array(Auth::user()->user_role, ['admin', 'super_admin'])) {
            abort(403, 'Không có quyền truy cập.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();

        $query = TournamentManagement::with(['game', 'creator'])
            ->orderBy('created_at', 'desc');

        // Filter by game
        if ($request->filled('game_id')) {
            $query->where('game_id', $request->game_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $tournaments = $query->paginate(12);
        $games = GameManagement::orderBy('name')->get();

        return view('admin.tournaments.index', compact('tournaments', 'games'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAdminAccess();
        $games = GameManagement::where('status', 'active')->orderBy('name')->get();

        return view('admin.tournaments.create', compact('games'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAdminAccess();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'game_id' => 'required|exists:games_management,id',
            'competition_type' => 'required|in:individual,team',
            'format' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:2000',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location_type' => 'required|in:online,lan,physical',
            'location_address' => 'nullable|string|max:500',
            'scheduled_time' => 'nullable|date_format:H:i',
            'tournament_format' => 'required|in:single_elimination,double_elimination,round_robin,swiss_system',
            'max_participants' => 'required|integer|min:2|max:1024',
            'substitute_players' => 'nullable|integer|min:0|max:10',
            'organizer_name' => 'nullable|string|max:255',
            'organizer_contact' => 'nullable|string|max:255',
            'status' => 'required|in:draft,registration_open,ongoing,completed,cancelled',
            'participation_type' => 'required|in:public,invite_only',
            'stream_link' => 'nullable|url|max:500',
            'logo' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'rules_details' => 'nullable|string',
            'referees' => 'nullable|string',
            'prize_structure' => 'nullable|string',
            'sponsors' => 'nullable|string',
            'hashtags' => 'nullable|string',
        ], [
            'name.required' => 'Tên giải đấu là bắt buộc.',
            'game_id.required' => 'Vui lòng chọn game.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'max_participants.min' => 'Số lượng tham gia tối thiểu là 2.',
            'logo.uploaded' => 'Logo không thể upload. Vui lòng chọn file nhỏ hơn 2MB.',
            'banner.uploaded' => 'Banner không thể upload. Vui lòng chọn file nhỏ hơn 5MB.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Process JSON fields
        if (isset($data['rules_details']) && $data['rules_details']) {
            $data['rules_details'] = json_decode($data['rules_details'], true);
        } else {
            $data['rules_details'] = null;
        }

        if (isset($data['referees']) && $data['referees']) {
            $data['referees'] = json_decode($data['referees'], true);
        } else {
            $data['referees'] = null;
        }

        if (isset($data['prize_structure']) && $data['prize_structure']) {
            $data['prize_structure'] = json_decode($data['prize_structure'], true);
        } else {
            $data['prize_structure'] = null;
        }

        if (isset($data['sponsors']) && $data['sponsors']) {
            $data['sponsors'] = json_decode($data['sponsors'], true);
        } else {
            $data['sponsors'] = null;
        }

        if (isset($data['hashtags']) && $data['hashtags']) {
            $data['hashtags'] = json_decode($data['hashtags'], true);
        } else {
            $data['hashtags'] = null;
        }

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            if ($logoFile->isValid()) {
                $data['logo'] = $logoFile->store('tournaments/logos', 'public');
            }
        }

        if ($request->hasFile('banner')) {
            $bannerFile = $request->file('banner');
            if ($bannerFile->isValid()) {
                $data['banner'] = $bannerFile->store('tournaments/banners', 'public');
            }
        }

        // Set created_by
        $data['created_by'] = Auth::id();
        TournamentManagement::create($data);

        return redirect()->route('admin.tournaments.index')
            ->with('success', 'Tournament đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TournamentManagement $tournament)
    {
        $this->checkAdminAccess();
        $tournament->load(['game', 'creator', 'updater']);

        return view('admin.tournaments.show', compact('tournament'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TournamentManagement $tournament)
    {
        $this->checkAdminAccess();
        $games = GameManagement::where('status', 'active')->orderBy('name')->get();

        return view('admin.tournaments.edit', compact('tournament', 'games'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TournamentManagement $tournament)
    {
        $this->checkAdminAccess();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'game_id' => 'required|exists:games_management,id',
            'competition_type' => 'required|in:individual,team',
            'format' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:2000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location_type' => 'required|in:online,lan,physical',
            'location_address' => 'nullable|string|max:500',
            'scheduled_time' => 'nullable|date_format:H:i',
            'tournament_format' => 'required|in:single_elimination,double_elimination,round_robin,swiss_system',
            'max_participants' => 'required|integer|min:2|max:1024',
            'substitute_players' => 'nullable|integer|min:0|max:10',
            'organizer_name' => 'nullable|string|max:255',
            'organizer_contact' => 'nullable|string|max:255',
            'status' => 'required|in:draft,registration_open,ongoing,completed,cancelled',
            'participation_type' => 'required|in:public,invite_only',
            'stream_link' => 'nullable|url|max:500',
            'logo' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'rules_details' => 'nullable|string',
            'referees' => 'nullable|string',
            'prize_structure' => 'nullable|string',
            'sponsors' => 'nullable|string',
            'hashtags' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Process JSON fields
        if (isset($data['rules_details']) && $data['rules_details']) {
            $data['rules_details'] = json_decode($data['rules_details'], true);
        } else {
            $data['rules_details'] = null;
        }

        if (isset($data['referees']) && $data['referees']) {
            $data['referees'] = json_decode($data['referees'], true);
        } else {
            $data['referees'] = null;
        }

        if (isset($data['prize_structure']) && $data['prize_structure']) {
            $data['prize_structure'] = json_decode($data['prize_structure'], true);
        } else {
            $data['prize_structure'] = null;
        }

        if (isset($data['sponsors']) && $data['sponsors']) {
            $data['sponsors'] = json_decode($data['sponsors'], true);
        } else {
            $data['sponsors'] = null;
        }

        if (isset($data['hashtags']) && $data['hashtags']) {
            $data['hashtags'] = json_decode($data['hashtags'], true);
        } else {
            $data['hashtags'] = null;
        }

        // Handle file uploads and delete old files
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            if ($logoFile->isValid()) {
                if ($tournament->logo) {
                    Storage::disk('public')->delete($tournament->logo);
                }
                $data['logo'] = $logoFile->store('tournaments/logos', 'public');
            }
        }

        if ($request->hasFile('banner')) {
            $bannerFile = $request->file('banner');
            if ($bannerFile->isValid()) {
                if ($tournament->banner) {
                    Storage::disk('public')->delete($tournament->banner);
                }
                $data['banner'] = $bannerFile->store('tournaments/banners', 'public');
            }
        }

        // Set updated_by
        $data['updated_by'] = Auth::id();

        $tournament->update($data);

        return redirect()->route('admin.tournaments.index')
            ->with('success', 'Tournament đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TournamentManagement $tournament)
    {
        $this->checkAdminAccess();

        // Delete associated files
        if ($tournament->logo) {
            Storage::disk('public')->delete($tournament->logo);
        }
        if ($tournament->banner) {
            Storage::disk('public')->delete($tournament->banner);
        }

        $tournament->delete();

        return response()->json(['success' => true, 'message' => 'Tournament đã được xóa thành công!']);
    }

    /**
     * Get tournaments for API
     */
    public function getTournaments(Request $request)
    {
        $this->checkAdminAccess();

        $tournaments = TournamentManagement::with(['game'])
            ->when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->game_id, function ($query) use ($request) {
                return $query->where('game_id', $request->game_id);
            })
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json($tournaments);
    }
}
