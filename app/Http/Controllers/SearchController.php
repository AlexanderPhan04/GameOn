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
                'users' => [], 'teams' => [], 'tournaments' => [],
            ]);
        }

        $limit = (int) ($request->get('limit', 5));
        $user = Auth::user();
        $isAdmin = $user && in_array($user->user_role, ['admin', 'super_admin']);

        $users = User::query()
            ->where(function ($w) use ($q) {
                $w->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhereHas('profile', function ($p) use ($q) {
                        $p->where('id_app', 'like', "%$q%")
                            ->orWhere('full_name', 'like', "%$q%");
                    });
            })
            ->with('profile:user_id,avatar,id_app,full_name')
            ->limit($limit)
            ->get(['id', 'name', 'email']);

        $teams = Team::query()
            ->where(function ($w) use ($q) {
                $w->where('name', 'like', "%$q%")
                    ->orWhere('name', 'like', "%" . str_replace(' ', '', $q) . "%")
                    ->orWhere('name', 'like', "%" . str_replace(' ', '%', $q) . "%");
            })
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

        // Games only for admin
        $games = collect();
        if ($isAdmin) {
            $games = GameManagement::query()
                ->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%$q%")
                        ->orWhere('name', 'like', "%" . str_replace(' ', '', $q) . "%")
                        ->orWhere('name', 'like', "%" . str_replace(' ', '%', $q) . "%");
                })
                ->limit($limit)
                ->get(['id', 'name']);
            if ($games->isEmpty()) {
                $games = Game::query()
                    ->where(function ($w) use ($q) {
                        $w->where('name', 'like', "%$q%")
                            ->orWhere('name', 'like', "%" . str_replace(' ', '', $q) . "%")
                            ->orWhere('name', 'like', "%" . str_replace(' ', '%', $q) . "%");
                    })
                    ->limit($limit)
                    ->get(['id', 'name']);
            }
        }

        // Build URLs to relevant index pages with search params
        $map = function ($items, $type) use ($user) {
            return $items->map(function ($item) use ($type, $user) {
                $name = $item->name ?? '';
                $base = '#';
                if ($type === 'users') {
                    if ($user && $item->id == $user->id) {
                        $base = route('profile.show');
                    } else {
                        $idApp = $item->profile?->id_app ?? $item->id;
                        $base = route('profile.show-user', $idApp);
                    }
                } elseif ($type === 'teams') {
                    $base = route('teams.show', $item->id);
                } elseif ($type === 'tournaments') {
                    $base = route('tournaments.show', $item->id);
                } elseif ($type === 'games') {
                    $base = route('admin.games.show', $item->id);
                }

                return [
                    'id' => $item->id,
                    'name' => $name,
                    'type' => rtrim($type, 's'),
                    'url' => $base,
                ];
            });
        };

        $result = [
            'users' => $map($users, 'users'),
            'teams' => $map($teams, 'teams'),
            'tournaments' => $map($tournaments, 'tournaments'),
        ];
        
        if ($isAdmin) {
            $result['games'] = $map($games, 'games');
        }

        return response()->json($result);
    }

    public function view(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $limit = (int) ($request->get('limit', 15));
        $type = $request->get('type'); // users|teams|tournaments|games|null (all)
        $user = Auth::user();
        $isAdmin = $user && in_array($user->user_role, ['admin', 'super_admin']);

        $users = $teams = $tournaments = $games = collect();

        if ($q !== '') {
            if (! $type || $type === 'users') {
                $users = User::query()
                    ->where(function ($w) use ($q) {
                        $w->where('name', 'like', "%$q%")
                            ->orWhere('email', 'like', "%$q%")
                            ->orWhereHas('profile', function ($p) use ($q) {
                                $p->where('id_app', 'like', "%$q%")
                                    ->orWhere('full_name', 'like', "%$q%");
                            });
                    })
                    ->with('profile:user_id,avatar,id_app,full_name')
                    ->limit($limit)
                    ->get(['id', 'name', 'email']);
            }
            if (! $type || $type === 'teams') {
                $teams = Team::query()
                    ->where(function ($w) use ($q) {
                        $w->where('name', 'like', "%$q%")
                            ->orWhere('name', 'like', "%" . str_replace(' ', '', $q) . "%")
                            ->orWhere('name', 'like', "%" . str_replace(' ', '%', $q) . "%");
                    })
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
            // Games only for admin
            if ($isAdmin && (! $type || $type === 'games')) {
                $games = GameManagement::query()
                    ->where(function ($w) use ($q) {
                        $w->where('name', 'like', "%$q%")
                            ->orWhere('name', 'like', "%" . str_replace(' ', '', $q) . "%")
                            ->orWhere('name', 'like', "%" . str_replace(' ', '%', $q) . "%");
                    })
                    ->limit($limit)->get(['id', 'name']);
                if ($games->isEmpty()) {
                    $games = Game::query()
                        ->where(function ($w) use ($q) {
                            $w->where('name', 'like', "%$q%")
                                ->orWhere('name', 'like', "%" . str_replace(' ', '', $q) . "%")
                                ->orWhere('name', 'like', "%" . str_replace(' ', '%', $q) . "%");
                        })
                        ->limit($limit)->get(['id', 'name']);
                }
            }
        }

        $counts = [
            'users' => $q === '' ? 0 : User::where(function ($w) use ($q) {
                $w->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhereHas('profile', function ($p) use ($q) {
                        $p->where('id_app', 'like', "%$q%")
                            ->orWhere('full_name', 'like', "%$q%");
                    });
            })->count(),
            'teams' => $q === '' ? 0 : Team::where(function ($w) use ($q) {
                $w->where('name', 'like', "%$q%")
                    ->orWhere('name', 'like', "%" . str_replace(' ', '', $q) . "%")
                    ->orWhere('name', 'like', "%" . str_replace(' ', '%', $q) . "%");
            })->count(),
            'tournaments' => $q === '' ? 0 : (TournamentManagement::where('name', 'like', "%$q%")->count() ?: Tournament::where('name', 'like', "%$q%")->count()),
        ];
        
        // Games count only for admin
        if ($isAdmin) {
            $counts['games'] = $q === '' ? 0 : (GameManagement::where(function ($w) use ($q) {
                $w->where('name', 'like', "%$q%")
                    ->orWhere('name', 'like', "%" . str_replace(' ', '', $q) . "%");
            })->count() ?: Game::where(function ($w) use ($q) {
                $w->where('name', 'like', "%$q%")
                    ->orWhere('name', 'like', "%" . str_replace(' ', '', $q) . "%");
            })->count());
        }

        return view('search.index', compact('q', 'type', 'users', 'teams', 'tournaments', 'games', 'counts', 'user', 'isAdmin'));
    }
}
