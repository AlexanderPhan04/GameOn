<?php

namespace App\Http\Controllers;

use App\Events\TeamMemberChanged;
use App\Events\TeamMessageSent;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get teams where user is captain
        $teamsAsCaptain = Team::with(['captain', 'members', 'game'])
            ->where('captain_id', Auth::id())
            ->where('status', 'active')
            ->get();

        // Get all teams for display
        $allTeams = Team::with(['captain', 'members', 'game'])
            ->where('status', 'active')
            ->paginate(12);

        // Merge teams as captain and all teams for pagination compatibility
        $teams = $allTeams;

        return view('teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $games = \App\Models\Game::where('status', 'active')->get();

        return view('teams.create', compact('games'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams',
            'description' => 'nullable|string|max:1000',
            'game_id' => 'nullable|exists:games,id',
            'max_members' => 'nullable|integer|min:2|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $logoUrl = null;
        if ($request->hasFile('logo')) {
            $logoUrl = $request->file('logo')->store('team_logos', 'public');
        }

        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'game_id' => $request->game_id,
            'logo_url' => $logoUrl,
            'created_by' => Auth::id(),
            'captain_id' => Auth::id(),
            'max_members' => $request->max_members ?? 10, // Default max members
            'status' => 'active',
        ]);

        // Add creator as first member
        $team->members()->attach(Auth::id(), [
            'role' => 'captain',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        return redirect()->route('teams.show', $team->id)
            ->with('success', 'Đội đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        $team->load(['captain', 'members', 'creator', 'game']);

        return view('teams.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        $this->authorize('update', $team);

        return view('teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name,' . $team->id,
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $logoUrl = $team->logo_url;
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($team->logo_url) {
                Storage::disk('public')->delete($team->logo_url);
            }
            $logoUrl = $request->file('logo')->store('team_logos', 'public');
        }

        $team->update([
            'name' => $request->name,
            'description' => $request->description,
            'logo_url' => $logoUrl,
        ]);

        return redirect()->route('teams.show', $team->id)
            ->with('success', 'Đội đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);

        // Delete logo if exists
        if ($team->logo_url) {
            Storage::disk('public')->delete($team->logo_url);
        }

        // Remove all members
        $team->members()->detach();

        $team->delete();

        return redirect()->route('teams.index')
            ->with('success', 'Đội đã được xóa thành công!');
    }

    /**
     * Join a team
     */
    public function join(Request $request, Team $team)
    {
        $user = Auth::user();

        // Check if user is already a member
        if ($team->isMember($user)) {
            return back()->with('error', 'Bạn đã là thành viên của đội này!');
        }

        // Check if team has space
        if (! $team->canJoin()) {
            return back()->with('error', 'Đội đã đầy thành viên!');
        }

        $team->members()->attach($user->id, [
            'role' => 'member',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        return back()->with('success', 'Bạn đã tham gia đội thành công!');
    }

    /**
     * Leave a team
     */
    public function leave(Request $request, Team $team)
    {
        $user = Auth::user();

        // Check if user is a member
        if (! $team->isMember($user)) {
            return back()->with('error', 'Bạn không phải là thành viên của đội này!');
        }

        // Captain cannot leave unless transferring captaincy
        if ($team->isCaptain($user)) {
            return back()->with('error', 'Đội trưởng không thể rời đội! Hãy chuyển giao quyền đội trưởng trước.');
        }

        $team->members()->detach($user->id);

        return back()->with('success', 'Bạn đã rời khỏi đội!');
    }

    /**
     * Transfer captaincy
     */
    public function transferCaptain(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $request->validate([
            'new_captain_id' => 'required|exists:users,id',
        ]);

        $newCaptain = User::find($request->new_captain_id);

        // Check if new captain is a member
        if (! $team->isMember($newCaptain)) {
            return back()->with('error', 'Người được chọn không phải là thành viên của đội!');
        }

        // Update team captain
        $team->update(['captain_id' => $newCaptain->id]);

        // Update member roles
        $team->members()->updateExistingPivot(Auth::id(), ['role' => 'member']);
        $team->members()->updateExistingPivot($newCaptain->id, ['role' => 'captain']);

        return back()->with('success', 'Đã chuyển giao quyền đội trưởng thành công!');
    }

    /**
     * Kick a member
     */
    public function kickMember(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $request->validate([
            'member_id' => 'required|exists:users,id',
        ]);

        $member = User::find($request->member_id);

        // Cannot kick captain
        if ($team->isCaptain($member)) {
            return back()->with('error', 'Không thể đuổi đội trưởng!');
        }

        // Check if member is in team
        if (! $team->isMember($member)) {
            return back()->with('error', 'Người này không phải là thành viên của đội!');
        }

        $team->members()->detach($member->id);

        return back()->with('success', 'Đã đuổi thành viên khỏi đội!');
    }

    /**
     * Invite a member to team
     */
    public function inviteMember(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if user is already a member
        if ($team->isMember($user)) {
            return back()->with('error', 'Người này đã là thành viên của đội!');
        }

        // Check if team has space
        if (! $team->canJoin()) {
            return back()->with('error', 'Đội đã đầy thành viên!');
        }

        $team->members()->attach($user->id, [
            'role' => 'member',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        return back()->with('success', "Đã mời {$user->name} vào đội thành công!");
    }

    /**
     * Search users to add to team
     */
    public function searchUsers(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json(['users' => []]);
        }

        // Get existing member IDs
        $existingMemberIds = $team->members->pluck('id')->toArray();

        $users = User::where('user_role', 'participant')
            ->where('status', 'active')
            ->whereNotIn('id', $existingMemberIds)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->display_name,
                    'email' => $user->email,
                    'avatar' => $user->avatar ? get_avatar_url($user->avatar) : null,
                ];
            });

        return response()->json(['users' => $users]);
    }

    /**
     * Add member to team
     */
    public function addMember(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // Check if already a member
        if ($team->members->contains($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Người dùng đã là thành viên của đội',
            ]);
        }

        // Check max members
        if ($team->members->count() >= ($team->max_members ?? 10)) {
            return response()->json([
                'success' => false,
                'message' => 'Đội đã đủ số lượng thành viên tối đa',
            ]);
        }

        $team->members()->attach($user->id, [
            'role' => 'member',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        // Broadcast member added event
        event(new TeamMemberChanged($team, $user, 'added'));

        return response()->json([
            'success' => true,
            'message' => "Đã thêm {$user->display_name} vào đội",
        ]);
    }

    /**
     * Remove member from team
     */
    public function removeMember(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = $request->user_id;

        // Cannot remove captain
        if ($userId == $team->captain_id) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa đội trưởng khỏi đội',
            ]);
        }

        $user = User::findOrFail($userId);
        $team->members()->detach($userId);

        // Broadcast member removed event
        event(new TeamMemberChanged($team, $user, 'removed'));

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa thành viên khỏi đội',
        ]);
    }

    /**
     * Get team chat messages
     */
    public function getMessages(Team $team)
    {
        // Check if user is a member
        if (!$team->members->contains(Auth::id())) {
            return response()->json(['messages' => []]);
        }

        $messages = \App\Models\TeamMessage::where('team_id', $team->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->limit(50)
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'time' => $msg->created_at->format('H:i'),
                    'user' => [
                        'id' => $msg->user->id,
                        'name' => $msg->user->display_name,
                        'avatar' => $msg->user->avatar ? get_avatar_url($msg->user->avatar) : null,
                    ],
                ];
            });

        return response()->json(['messages' => $messages]);
    }

    /**
     * Send chat message
     */
    public function sendMessage(Request $request, Team $team)
    {
        // Check if user is a member
        if (!$team->members->contains(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không phải là thành viên của đội',
            ]);
        }

        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $message = \App\Models\TeamMessage::create([
            'team_id' => $team->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Load user relationship and broadcast
        $message->load('user');
        event(new TeamMessageSent($message));

        return response()->json(['success' => true]);
    }
}
