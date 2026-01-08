<?php

namespace App\Http\Controllers;

use App\Events\TeamInvitationSent;
use App\Events\TeamMemberChanged;
use App\Events\TeamMessageSent;
use App\Models\Team;
use App\Models\TeamInvitation;
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
        // Get teams where user is member
        $myTeams = Team::with(['captain', 'members', 'game'])
            ->whereHas('members', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('status', 'active')
            ->get();

        // Get pending invitations for current user
        $pendingInvitations = TeamInvitation::with(['team.captain', 'team.game', 'inviter'])
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all teams for display
        $allTeams = Team::with(['captain', 'members', 'game'])
            ->where('status', 'active')
            ->paginate(12);

        $teams = $allTeams;

        return view('teams.index', compact('teams', 'myTeams', 'pendingInvitations'));
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

        // Broadcast member left event for realtime updates
        event(new TeamMemberChanged($team, $user, 'removed'));

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
     * Send invitation to join team (instead of adding directly)
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

        // Check if already has pending invitation
        $existingInvitation = TeamInvitation::where('team_id', $team->id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existingInvitation) {
            return response()->json([
                'success' => false,
                'message' => 'Đã gửi lời mời cho người dùng này rồi',
            ]);
        }

        // Check max members
        if ($team->members->count() >= ($team->max_members ?? 10)) {
            return response()->json([
                'success' => false,
                'message' => 'Đội đã đủ số lượng thành viên tối đa',
            ]);
        }

        // Create invitation
        $invitation = TeamInvitation::create([
            'team_id' => $team->id,
            'user_id' => $user->id,
            'invited_by' => Auth::id(),
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);

        // Load relationships and broadcast
        $invitation->load(['team', 'inviter']);
        
        // Create notification in database
        \App\Models\Notification::createTeamInvitation($user->id, [
            'inviter_name' => Auth::user()->display_name,
            'team_name' => $team->name,
            'team_logo' => $team->logo_url,
            'team_id' => $team->id,
            'invitation_id' => $invitation->id,
        ]);
        
        event(new TeamInvitationSent($invitation));

        return response()->json([
            'success' => true,
            'message' => "Đã gửi lời mời đến {$user->display_name}",
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

    /**
     * Get pending invitations for current user
     */
    public function myInvitations()
    {
        $invitations = TeamInvitation::with(['team', 'inviter'])
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['invitations' => $invitations]);
    }

    /**
     * Accept team invitation
     */
    public function acceptInvitation($id)
    {
        try {
            $invitation = TeamInvitation::findOrFail($id);
            
            // Check if invitation belongs to current user
            if ((int) $invitation->user_id !== (int) Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền thực hiện hành động này',
                ], 403);
            }

            // Check if invitation is still valid
            if (!$invitation->isPending()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lời mời đã hết hạn hoặc không còn hiệu lực',
                ]);
            }

            $team = $invitation->team;

            // Check if already a member
            if ($team->members->contains(Auth::id())) {
                $invitation->update(['status' => 'accepted']);
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã là thành viên của đội này',
                ]);
            }

            // Check max members
            if ($team->members->count() >= ($team->max_members ?? 10)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đội đã đủ số lượng thành viên tối đa',
                ]);
            }

            // Add to team
            $team->members()->attach(Auth::id(), [
                'role' => 'member',
                'status' => 'active',
                'joined_at' => now(),
            ]);

            // Update invitation status
            $invitation->update(['status' => 'accepted']);

            // Broadcast member added
            event(new TeamMemberChanged($team, Auth::user(), 'added'));

            return response()->json([
                'success' => true,
                'message' => "Bạn đã tham gia đội {$team->name}",
                'redirect' => route('teams.show', $team->id),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lời mời không tồn tại',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Decline team invitation
     */
    public function declineInvitation($id)
    {
        try {
            $invitation = TeamInvitation::findOrFail($id);
            
            // Check if invitation belongs to current user
            if ((int) $invitation->user_id !== (int) Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền thực hiện hành động này',
                ], 403);
            }

            $invitation->update(['status' => 'declined']);

            return response()->json([
                'success' => true,
                'message' => 'Đã từ chối lời mời',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lời mời không tồn tại',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ], 500);
        }
    }
}
