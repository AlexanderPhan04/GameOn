<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
use App\Models\TournamentManagement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        // Always show home page with general information
        $stats = [
            'total_users' => User::count(),
            'total_teams' => Team::count(),
            'active_tournaments' => TournamentManagement::where('status', 'active')->count(),
            'featured_tournaments' => TournamentManagement::with(['game', 'creator'])
                ->where('status', 'active')
                ->orderBy('start_date', 'desc')
                ->take(3)
                ->get(),
            'top_teams' => Team::withCount('activeMembers')
                ->where('status', 'active')
                ->orderBy('active_members_count', 'desc')
                ->take(4)
                ->get(),
        ];

        return view('welcome', compact('stats'));
    }

    /**
     * Display dashboard based on user role
     */
    public function dashboard()
    {
        $user = Auth::user();

        switch ($user->user_role) {
            case 'super_admin':
                return $this->superAdminDashboard();
            case 'admin':
                return $this->adminDashboard();
            case 'player':
                return $this->playerDashboard();
            case 'viewer':
                return $this->viewerDashboard();
            default:
                return $this->viewerDashboard();
        }
    }

    /**
     * Super Admin Dashboard
     */
    private function superAdminDashboard()
    {
        // Get user growth data for the last 12 months
        $userGrowthData = [];
        $userGrowthLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $userGrowthLabels[] = $date->format('M');
            $userGrowthData[] = User::whereBetween('created_at', [$monthStart, $monthEnd])->count();
        }

        // Get total games
        $totalGames = Game::count();

        // Get active tournaments
        $activeTournaments = TournamentManagement::where('status', 'active')->count();

        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('user_role', 'admin')->count(),
            'total_players' => User::where('user_role', 'player')->count(),
            'total_viewers' => User::where('user_role', 'viewer')->count(),
            'total_teams' => Team::count(),
            'active_teams' => Team::where('status', 'active')->count(),
            'total_games' => $totalGames,
            'active_tournaments' => $activeTournaments,
            'user_growth_labels' => $userGrowthLabels,
            'user_growth_data' => $userGrowthData,
            'recent_users' => User::select(['id', 'name', 'full_name', 'email', 'avatar', 'user_role', 'status', 'created_at'])
                ->orderBy('created_at', 'desc')
                ->paginate(5, ['*'], 'users_page'),
            'user_roles_distribution' => User::selectRaw('user_role, COUNT(*) as count')
                ->groupBy('user_role')
                ->get(),
        ];

        return view('dashboard.super-admin', compact('stats'));
    }

    /**
     * Get Recent Users HTML for AJAX pagination
     */
    public function getRecentUsers()
    {
        $recentUsers = User::select(['id', 'name', 'full_name', 'email', 'avatar', 'user_role', 'status', 'created_at'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'users_page');

        return view('dashboard.partials.recent-users', ['recent_users' => $recentUsers])->render();
    }

    /**
     * Admin Dashboard
     */
    private function adminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_players' => User::where('user_role', 'player')->count(),
            'total_teams' => Team::count(),
            'active_teams' => Team::where('status', 'active')->count(),
            'recent_users' => User::select(['id', 'name', 'full_name', 'email', 'avatar', 'user_role', 'status', 'created_at'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];

        return view('dashboard.admin', compact('stats'));
    }

    /**
     * Player Dashboard
     */
    private function playerDashboard()
    {
        $user = User::with(['teams.captain', 'teams.activeMembers', 'captainTeams.activeMembers'])
            ->find(Auth::id());

        $data = [
            'user' => $user,
            'my_teams' => $user->teams ?? collect(),
            'captain_teams' => $user->captainTeams ?? collect(),
            'team_invitations' => [], // TODO: Implement team invitations
            'recent_tournaments' => [], // TODO: Get recent tournaments
        ];

        return view('dashboard.player', $data);
    }

    /**
     * Viewer Dashboard
     */
    private function viewerDashboard()
    {
        $data = [
            'featured_tournaments' => [], // TODO: Get featured tournaments
            'popular_teams' => Team::withCount('activeMembers')
                ->orderBy('active_members_count', 'desc')
                ->take(6)
                ->get(),
            'recent_matches' => [], // TODO: Get recent matches
        ];

        return view('dashboard.viewer', $data);
    }
}
