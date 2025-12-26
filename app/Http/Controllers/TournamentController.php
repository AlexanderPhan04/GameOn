<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentManagement;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    /**
     * Display a listing of tournaments for public view.
     */
    public function index(Request $request)
    {
        $query = TournamentManagement::with(['game', 'creator']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by game
        if ($request->filled('game_id')) {
            $query->where('game_id', $request->game_id);
        }

        // Search by name or description
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('description', 'LIKE', '%'.$request->search.'%');
            });
        }

        $tournaments = $query->orderBy('start_date', 'desc')
            ->paginate(12);

        $stats = [
            'total_tournaments' => TournamentManagement::count(),
            'active_tournaments' => TournamentManagement::whereIn('status', ['registration_open', 'ongoing'])->count(),
            'upcoming_tournaments' => TournamentManagement::where('status', 'registration_open')->count(),
            'completed_tournaments' => TournamentManagement::where('status', 'completed')->count(),
        ];

        return view('tournaments.index', compact('tournaments', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tournaments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement tournament creation logic
        return redirect()->route('tournaments.index')->with('success', 'Giải đấu đã được tạo thành công!');
    }

    /**
     * Display the specified tournament.
     */
    public function show(string $id)
    {
        $tournament = TournamentManagement::with(['game', 'creator'])
            ->findOrFail($id);

        return view('tournaments.show', compact('tournament'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('tournaments.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // TODO: Implement tournament update logic
        return redirect()->route('tournaments.index')->with('success', 'Giải đấu đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // TODO: Implement tournament deletion logic
        return redirect()->route('tournaments.index')->with('success', 'Giải đấu đã được xóa thành công!');
    }
}
