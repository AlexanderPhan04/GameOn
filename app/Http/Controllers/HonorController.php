<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\HonorEvent;
use App\Models\HonorVote;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HonorController extends Controller
{
    /**
     * Hiển thị trang vote chính
     */
    public function index()
    {
        $currentEvent = HonorEvent::currentlyRunning()->first();
        $freeModeEvents = HonorEvent::freeMode()->active()->get();

        return view('honor.index', compact('currentEvent', 'freeModeEvents'));
    }

    /**
     * Hiển thị trang vote cho một event cụ thể
     */
    public function show(HonorEvent $honorEvent)
    {
        if (! $honorEvent->isCurrentlyRunning()) {
            return redirect()->route('honor.index')->with('error', 'Đợt vote này không còn hoạt động.');
        }

        if (! Auth::check() || ! $honorEvent->canUserVote(Auth::user())) {
            return redirect()->route('honor.index')->with('error', 'Bạn không có quyền tham gia đợt vote này.');
        }

        // Lấy danh sách đối tượng có thể vote
        $votableItems = $this->getVotableItems($honorEvent);

        // Lấy vote hiện tại của user (nếu có)
        $userVote = HonorVote::where('honor_event_id', $honorEvent->id)
            ->where('voter_id', Auth::id())
            ->first();

        return view('honor.vote', compact('honorEvent', 'votableItems', 'userVote'));
    }

    /**
     * Xử lý vote
     */
    public function vote(Request $request, HonorEvent $honorEvent)
    {
        $request->validate([
            'voted_item_id' => 'required|integer',
            'comment' => 'nullable|string|max:500',
            'is_anonymous' => 'boolean',
        ]);

        if (! $honorEvent->isCurrentlyRunning()) {
            return response()->json(['success' => false, 'message' => 'Đợt vote này không còn hoạt động.']);
        }

        if (! $honorEvent->canUserVote(Auth::user())) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền tham gia đợt vote này.']);
        }

        $votedItemId = $request->voted_item_id;
        $votedItem = $this->getVotedItemById($honorEvent->target_type, $votedItemId);

        if (! $votedItem) {
            return response()->json(['success' => false, 'message' => 'Đối tượng vote không tồn tại.']);
        }

        // Kiểm tra không được vote cho chính mình (nếu là player)
        if ($honorEvent->target_type === 'player' && $votedItemId == Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Bạn không thể vote cho chính mình.']);
        }

        // Kiểm tra không được vote cho team của mình (nếu là team)
        if ($honorEvent->target_type === 'team') {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $userTeam = $user->teams()->where('teams.id', $votedItemId)->exists();
            if ($userTeam) {
                return response()->json(['success' => false, 'message' => 'Bạn không thể vote cho team của mình.']);
            }
        }

        try {
            DB::beginTransaction();

            // Xóa vote cũ nếu có
            HonorVote::where('honor_event_id', $honorEvent->id)
                ->where('voter_id', Auth::id())
                ->delete();

            // Tạo vote mới
            $weight = $honorEvent->getWeightForRole(Auth::user()->user_role);

            $vote = HonorVote::create([
                'honor_event_id' => $honorEvent->id,
                'voter_id' => Auth::id(),
                'voted_user_id' => $this->getUserIdFromItem($honorEvent->target_type, $votedItem),
                'vote_type' => $honorEvent->target_type,
                'voted_item_id' => $votedItemId,
                'voter_role' => Auth::user()->user_role,
                'weight' => $weight,
                'is_anonymous' => $request->boolean('is_anonymous'),
                'comment' => $request->comment,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vote thành công!',
                'vote' => $vote,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi vote.']);
        }
    }

    /**
     * Xem kết quả vote
     */
    public function results(HonorEvent $honorEvent)
    {
        $results = $this->getVoteResults($honorEvent);

        return view('honor.results', compact('honorEvent', 'results'));
    }

    /**
     * Lấy danh sách đối tượng có thể vote
     */
    private function getVotableItems(HonorEvent $honorEvent)
    {
        switch ($honorEvent->target_type) {
            case 'player':
                return User::where('user_role', 'player')
                    ->where('id', '!=', Auth::id()) // Không vote cho chính mình
                    ->select('id', 'name', 'display_name', 'avatar')
                    ->get();

            case 'team':
                return Team::where('is_active', true)
                    ->whereDoesntHave('members', function ($query) {
                        $query->where('user_id', Auth::id());
                    })
                    ->select('id', 'name', 'logo', 'description')
                    ->get();

            case 'tournament':
                return Tournament::where('status', 'active')
                    ->select('id', 'name', 'description', 'prize_pool')
                    ->get();

            case 'game':
                return Game::where('is_active', true)
                    ->select('id', 'name', 'description', 'image')
                    ->get();

            default:
                return collect();
        }
    }

    /**
     * Lấy đối tượng vote theo ID
     */
    private function getVotedItemById(string $type, int $id)
    {
        switch ($type) {
            case 'player':
                return User::find($id);
            case 'team':
                return Team::find($id);
            case 'tournament':
                return Tournament::find($id);
            case 'game':
                return Game::find($id);
            default:
                return null;
        }
    }

    /**
     * Lấy User ID từ đối tượng vote
     */
    private function getUserIdFromItem(string $type, $item)
    {
        switch ($type) {
            case 'player':
                return $item->id;
            case 'team':
                return $item->captain_id ?? $item->members()->first()?->user_id;
            case 'tournament':
                return $item->created_by ?? 1; // Fallback to admin
            case 'game':
                return 1; // Admin created games
            default:
                return 1;
        }
    }

    /**
     * Lấy kết quả vote
     */
    private function getVoteResults(HonorEvent $honorEvent)
    {
        return HonorVote::where('honor_event_id', $honorEvent->id)
            ->select('voted_item_id', 'vote_type')
            ->selectRaw('SUM(weight) as total_weight')
            ->selectRaw('COUNT(*) as vote_count')
            ->groupBy('voted_item_id', 'vote_type')
            ->orderBy('total_weight', 'desc')
            ->get()
            ->map(function ($result) {
                $item = $this->getVotedItemById($result->vote_type, $result->voted_item_id);

                return [
                    'item' => $item,
                    'item_name' => $item ? $this->getItemName($item, $result->vote_type) : 'Unknown',
                    'total_weight' => $result->total_weight,
                    'vote_count' => $result->vote_count,
                ];
            });
    }

    /**
     * Lấy tên đối tượng vote
     */
    private function getItemName($item, string $type): string
    {
        switch ($type) {
            case 'player':
                return $item->name ?? $item->display_name ?? 'Unknown Player';
            case 'team':
                return $item->name ?? 'Unknown Team';
            case 'tournament':
                return $item->name ?? 'Unknown Tournament';
            case 'game':
                return $item->name ?? 'Unknown Game';
            default:
                return 'Unknown';
        }
    }
}
