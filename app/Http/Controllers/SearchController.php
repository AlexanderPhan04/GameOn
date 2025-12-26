<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameManagement;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentManagement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        if ($q === '') {
            return response()->json([
                'users' => [], 'teams' => [], 'tournaments' => [], 'games' => [],
            ]);
        }

        $limit = (int) ($request->get('limit', 5));
        $user = Auth::user();

        $users = User::query()
            ->where(function ($w) use ($q) {
                $w->where('name', 'like', "%$q%")
                    ->orWhere('full_name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
            })
            ->limit($limit)
            ->get(['id', 'name', 'email']);

        $teams = Team::query()
            ->where('name', 'like', "%$q%")
            ->limit($limit)
            ->get(['id', 'name']);

        // tournaments: prefer TournamentManagement then fallback to Tournament
        $tournaments = TournamentManagement::query()
            ->where('name', 'like', "%$q%")
            ->limit($limit)
            ->get(['id', 'name']);
        if ($tournaments->isEmpty()) {
            $tournaments = Tournament::query()
                ->where('name', 'like', "%$q%")
                ->limit($limit)
                ->get(['id', 'name']);
        }

        // Prefer admin GameManagement if exists; fallback to public Game
        $games = GameManagement::query()
            ->where('name', 'like', "%$q%")
            ->limit($limit)
            ->get(['id', 'name']);
        if ($games->isEmpty()) {
            $games = Game::query()
                ->where('name', 'like', "%$q%")
                ->limit($limit)
                ->get(['id', 'name']);
        }

        // Build URLs to relevant index pages with search params
        $map = function ($items, $type) use ($user) {
            return $items->map(function ($item) use ($type, $user) {
                $name = $item->name ?? ($item->full_name ?? '');
                $base = '#';
                if ($type === 'users') {
                    // For users, redirect to their profile page
                    // If it's the current user, redirect to their own profile page
                    if ($item->id == $user->id) {
                        $base = route('profile.show');
                    } else {
                        $base = route('profile.show-user', $item->id);
                    }
                } elseif ($type === 'teams') {
                    $base = route('teams.index', ['search' => $name]);
                } elseif ($type === 'tournaments') {
                    $base = route('tournaments.index', ['search' => $name]);
                } elseif ($type === 'games') {
                    $base = $user && in_array($user->user_role, ['admin', 'super_admin'])
                        ? route('admin.games.index', ['search' => $name])
                        : '#';
                }

                return [
                    'id' => $item->id,
                    'name' => $name,
                    'type' => rtrim($type, 's'),
                    'url' => $base,
                ];
            });
        };

        return response()->json([
            'users' => $map($users, 'users'),
            'teams' => $map($teams, 'teams'),
            'tournaments' => $map($tournaments, 'tournaments'),
            'games' => $map($games, 'games'),
        ]);
    }

    public function view(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $limit = (int) ($request->get('limit', 15));
        $type = $request->get('type'); // users|teams|tournaments|games|null (all)
        $user = Auth::user();

        $users = $teams = $tournaments = $games = collect();

        if ($q !== '') {
            if (! $type || $type === 'users') {
                $users = User::query()
                    ->where(function ($w) use ($q) {
                        $w->where('name', 'like', "%$q%")
                            ->orWhere('full_name', 'like', "%$q%")
                            ->orWhere('email', 'like', "%$q%");
                    })
                    ->limit($limit)
                    ->get(['id', 'name', 'email']);
            }
            if (! $type || $type === 'teams') {
                $teams = Team::query()->where('name', 'like', "%$q%")
                    ->limit($limit)->get(['id', 'name']);
            }
            if (! $type || $type === 'tournaments') {
                $tournaments = TournamentManagement::query()->where('name', 'like', "%$q%")
                    ->limit($limit)->get(['id', 'name']);
                if ($tournaments->isEmpty()) {
                    $tournaments = Tournament::query()->where('name', 'like', "%$q%")
                        ->limit($limit)->get(['id', 'name']);
                }
            }
            if (! $type || $type === 'games') {
                $games = GameManagement::query()->where('name', 'like', "%$q%")
                    ->limit($limit)->get(['id', 'name']);
                if ($games->isEmpty()) {
                    $games = Game::query()->where('name', 'like', "%$q%")
                        ->limit($limit)->get(['id', 'name']);
                }
            }
        }

        $counts = [
            'users' => $q === '' ? 0 : User::where(function ($w) use ($q) {
                $w->where('name', 'like', "%$q%")
                    ->orWhere('full_name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
            })->count(),
            'teams' => $q === '' ? 0 : Team::where('name', 'like', "%$q%")->count(),
            'tournaments' => $q === '' ? 0 : (TournamentManagement::where('name', 'like', "%$q%")->count() ?: Tournament::where('name', 'like', "%$q%")->count()),
            'games' => $q === '' ? 0 : (GameManagement::where('name', 'like', "%$q%")->count() ?: Game::where('name', 'like', "%$q%")->count()),
        ];

        return view('search.index', compact('q', 'type', 'users', 'teams', 'tournaments', 'games', 'counts', 'user'));
    }
}
