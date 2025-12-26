<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Display a listing of players for public view.
     */
    public function index(Request $request)
    {
        $query = User::with(['teams', 'captainTeams'])
            ->where('user_role', 'player');

        // Search by name or email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('display_name', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('name', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('email', 'LIKE', '%'.$request->search.'%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by team status
        if ($request->filled('team_status')) {
            if ($request->team_status === 'with_team') {
                $query->whereHas('teams');
            } elseif ($request->team_status === 'without_team') {
                $query->whereDoesntHave('teams');
            }
        }

        $players = $query->orderBy('created_at', 'desc')
            ->paginate(12);

        $stats = [
            'total_players' => User::where('user_role', 'player')->count(),
            'active_players' => User::where('user_role', 'player')->where('status', 'active')->count(),
            'players_with_teams' => User::where('user_role', 'player')->whereHas('teams')->count(),
            'team_captains' => User::where('user_role', 'player')->whereHas('captainTeams')->count(),
        ];

        return view('players.index', compact('players', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('players.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement player creation logic
        return redirect()->route('players.index')->with('success', 'Người chơi đã được thêm thành công!');
    }

    /**
     * Display the specified player.
     */
    public function show(string $id)
    {
        $player = User::with(['teams.captain', 'teams.game', 'captainTeams.activeMembers'])
            ->where('user_role', 'player')
            ->findOrFail($id);

        $stats = [
            'teams_joined' => $player->teams->count(),
            'teams_captained' => $player->captainTeams->count(),
            'total_team_members' => $player->captainTeams->sum(function ($team) {
                return $team->activeMembers->count();
            }),
        ];

        return view('players.show', compact('player', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('players.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // TODO: Implement player update logic
        return redirect()->route('players.index')->with('success', 'Thông tin người chơi đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // TODO: Implement player deletion logic
        return redirect()->route('players.index')->with('success', 'Người chơi đã được xóa!');
    }
}
