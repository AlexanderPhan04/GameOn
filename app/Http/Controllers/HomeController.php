<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
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
        try {
            $stats = [
                'total_users' => User::count(),
                'total_teams' => Schema::hasTable('teams') ? Team::count() : 0,
                'active_tournaments' => Schema::hasTable('tournaments')
                    ? Tournament::where('status', 'active')->count()
                    : 0,
                'featured_tournaments' => Schema::hasTable('tournaments')
                    ? Tournament::with(['game', 'creator'])
                    ->where('status', 'active')
                    ->orderBy('start_date', 'desc')
                    ->take(3)
                    ->get()
                    : collect(),
                'top_teams' => Schema::hasTable('teams')
                    ? Team::withCount('activeMembers')
                    ->where('status', 'active')
                    ->orderBy('active_members_count', 'desc')
                    ->take(4)
                    ->get()
                    : collect(),
            ];
        } catch (\Exception $e) {
            // Fallback nếu có lỗi
            $stats = [
                'total_users' => 0,
                'total_teams' => 0,
                'active_tournaments' => 0,
                'featured_tournaments' => collect(),
                'top_teams' => collect(),
            ];
        }

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
            case 'participant':
                // Participant không có dashboard, chuyển đến posts
                return redirect('/posts');
            case 'player':
                // Legacy: chuyển đến posts
                return redirect('/posts');
            case 'viewer':
                // Legacy: chuyển đến posts
                return redirect('/posts');
            default:
                return redirect('/posts');
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
        $activeTournaments = Tournament::where('status', 'active')->count();

        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('user_role', 'admin')->count(),
            'total_players' => User::where('user_role', 'player')->count(),
            'total_viewers' => User::where('user_role', 'viewer')->count(),
            'total_teams' => Schema::hasTable('teams') ? Team::count() : 0,
            'active_teams' => Schema::hasTable('teams') ? Team::where('status', 'active')->count() : 0,
            'total_games' => $totalGames,
            'active_tournaments' => $activeTournaments,
            'user_growth_labels' => $userGrowthLabels,
            'user_growth_data' => $userGrowthData,
            'recent_users' => User::with('profile')
                ->select(['id', 'name', 'email', 'user_role', 'status', 'created_at'])
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
        $recentUsers = User::with('profile')
            ->select(['id', 'name', 'email', 'user_role', 'status', 'created_at'])
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
            'total_teams' => Schema::hasTable('teams') ? Team::count() : 0,
            'active_teams' => Schema::hasTable('teams') ? Team::where('status', 'active')->count() : 0,
            'recent_users' => User::with('profile')
                ->select(['id', 'name', 'email', 'user_role', 'status', 'created_at'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];

        return view('dashboard.admin', compact('stats'));
    }
}
