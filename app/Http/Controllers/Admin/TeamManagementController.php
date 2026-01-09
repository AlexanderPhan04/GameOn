<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class TeamManagementController extends Controller
{
    /**
     * Check if user has admin access
     */
    private function checkAdminAccess()
    {
        if (! Auth::check() || ! in_array(Auth::user()->user_role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized access');
        }
    }

    /**
     * Display a listing of teams
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();

        $query = Team::with(['captain', 'game', 'members']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhereHas('captain', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($request->filled('game_id')) {
            $query->where('game_id', $request->game_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply sorting
        $sortField = $request->sort ?? 'created_at';
        $sortDirection = $request->direction ?? 'desc';

        $allowedSorts = ['created_at', 'name', 'status'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $teams = $query->paginate(15)->appends($request->query());
        $games = Game::where('status', 'active')->get();

        return view('admin.teams.index', compact('teams', 'games'));
    }

    /**
     * Display the specified team
     */
    public function show(Team $team)
    {
        $this->checkAdminAccess();

        $team->load(['captain', 'creator', 'game', 'members' => function ($query) {
            $query->withPivot(['role', 'status', 'joined_at'])->orderBy('team_members.joined_at');
        }]);

        return view('admin.teams.show', compact('team'));
    }

    /**
     * Update team status
     */
    public function updateStatus(Request $request, Team $team)
    {
        $this->checkAdminAccess();

        $request->validate([
            'status' => 'required|in:active,inactive,suspended,banned',
            'reason' => 'nullable|string|max:500',
        ]);

        $team->update([
            'status' => $request->status,
        ]);

        $statusNames = [
            'active' => __('app.teams.active'),
            'inactive' => __('app.teams.inactive'),
            'suspended' => __('app.teams.suspended'),
            'banned' => __('app.teams.banned'),
        ];

        return response()->json([
            'success' => true,
            'message' => __('app.teams.status_updated_successfully', ['status' => $statusNames[$request->status]]),
            'status_display' => $statusNames[$request->status],
            'badge_class' => $this->getStatusBadgeClass($request->status),
        ]);
    }

    /**
     * Get status badge class
     */
    private function getStatusBadgeClass($status)
    {
        return match ($status) {
            'active' => 'bg-success',
            'inactive' => 'bg-secondary',
            'suspended' => 'bg-warning',
            'banned' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    /**
     * Export teams data
     */
    public function export(Request $request)
    {
        $this->checkAdminAccess();

        $query = Team::with(['captain', 'game', 'members']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhereHas('captain', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($request->filled('game_id')) {
            $query->where('game_id', $request->game_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $teams = $query->get();

        $csvData = "ID,Tên đội,Đội trưởng,Email đội trưởng,Game,Số thành viên,Trạng thái,Ngày tạo\n";

        foreach ($teams as $team) {
            $csvData .= sprintf(
                "%d,%s,%s,%s,%s,%d,%s,%s\n",
                $team->id,
                '"' . str_replace('"', '""', $team->name) . '"',
                '"' . str_replace('"', '""', $team->captain->name ?? 'N/A') . '"',
                $team->captain->email ?? 'N/A',
                '"' . str_replace('"', '""', $team->game->name ?? 'N/A') . '"',
                $team->members->count(),
                $team->status,
                $team->created_at->format('d/m/Y H:i')
            );
        }

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="teams_export_' . date('Y-m-d') . '.csv"',
        ]);
    }

    /**
     * Bulk update teams
     */
    public function bulkUpdate(Request $request)
    {
        $this->checkAdminAccess();

        $request->validate([
            'team_ids' => 'required|array',
            'team_ids.*' => 'exists:teams,id',
            'action' => 'required|in:activate,suspend,ban,delete',
        ]);

        $actionMap = [
            'activate' => 'active',
            'suspend' => 'suspended',
            'ban' => 'banned',
            'delete' => 'deleted',
        ];

        $status = $actionMap[$request->action];

        Team::whereIn('id', $request->team_ids)->update([
            'status' => $status,
            'updated_at' => now(),
        ]);

        $actionNames = [
            'activate' => 'kích hoạt',
            'suspend' => 'tạm khóa',
            'ban' => 'cấm',
            'delete' => 'xóa',
        ];

        $count = count($request->team_ids);
        $actionName = $actionNames[$request->action];

        return response()->json([
            'success' => true,
            'message' => "Đã {$actionName} {$count} đội thành công",
        ]);
    }
}
