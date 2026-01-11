<?php

namespace App\Http\Controllers\Admin;

use App\Events\HonorEventCreated;
use App\Events\HonorEventDeleted;
use App\Events\HonorEventUpdated;
use App\Events\HonorVotesReset;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\HonorEvent;
use App\Models\HonorVote;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HonorManagementController extends Controller
{
    /**
     * Hiển thị danh sách đợt vote
     */
    public function index()
    {
        $events = HonorEvent::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.honor.index', compact('events'));
    }

    /**
     * Hiển thị form tạo đợt vote mới
     */
    public function create()
    {
        return view('admin.honor.create');
    }

    /**
     * Lưu đợt vote mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'mode' => 'required|in:free,event',
            'target_type' => 'required|in:user,team,tournament,game',
            'start_time' => 'nullable|date|after:now',
            'end_time' => 'nullable|date|after:start_time',
            'allow_participant_vote' => 'boolean',
            'allow_admin_vote' => 'boolean',
            'allow_anonymous' => 'boolean',
            'participant_weight' => 'required|numeric|min:0.1|max:10',
            'admin_weight' => 'required|numeric|min:0.1|max:10',
        ]);

        try {
            $event = HonorEvent::create([
                'name' => $request->name,
                'description' => $request->description,
                'mode' => $request->mode,
                'target_type' => $request->target_type,
                'start_time' => $request->start_time ? Carbon::parse($request->start_time) : null,
                'end_time' => $request->end_time ? Carbon::parse($request->end_time) : null,
                'is_active' => true,
                'allow_participant_vote' => $request->boolean('allow_participant_vote'),
                'allow_admin_vote' => $request->boolean('allow_admin_vote'),
                'allow_anonymous' => $request->boolean('allow_anonymous'),
                'participant_weight' => $request->participant_weight,
                'admin_weight' => $request->admin_weight,
                'created_by' => Auth::id(),
            ]);

            // Broadcast event created realtime
            broadcast(new HonorEventCreated($event));

            return redirect()->route('admin.honor.index')
                ->with('success', 'Đợt vote đã được tạo thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo đợt vote.');
        }
    }

    /**
     * Hiển thị chi tiết đợt vote
     */
    public function show(HonorEvent $honorEvent)
    {
        $honorEvent->load(['creator', 'votes.voter', 'votes.votedUser']);

        // Thống kê vote
        $stats = [
            'total_votes' => $honorEvent->getTotalVotesCount(),
            'total_weighted_votes' => $honorEvent->getTotalWeightedVotes(),
            'votes_by_role' => $this->getVotesByRole($honorEvent),
            'top_items' => $this->getTopVotedItems($honorEvent),
        ];

        return view('admin.honor.show', compact('honorEvent', 'stats'));
    }

    /**
     * Hiển thị form chỉnh sửa đợt vote
     */
    public function edit(HonorEvent $honorEvent)
    {
        return view('admin.honor.edit', compact('honorEvent'));
    }

    /**
     * Cập nhật đợt vote
     */
    public function update(Request $request, HonorEvent $honorEvent)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'allow_participant_vote' => 'boolean',
            'allow_admin_vote' => 'boolean',
            'allow_anonymous' => 'boolean',
            'participant_weight' => 'required|numeric|min:0.1|max:10',
            'admin_weight' => 'required|numeric|min:0.1|max:10',
        ]);

        try {
            $honorEvent->update([
                'name' => $request->name,
                'description' => $request->description,
                'start_time' => $request->start_time ? Carbon::parse($request->start_time) : null,
                'end_time' => $request->end_time ? Carbon::parse($request->end_time) : null,
                'allow_participant_vote' => $request->boolean('allow_participant_vote'),
                'allow_admin_vote' => $request->boolean('allow_admin_vote'),
                'allow_anonymous' => $request->boolean('allow_anonymous'),
                'participant_weight' => $request->participant_weight,
                'admin_weight' => $request->admin_weight,
            ]);

            // Broadcast event updated realtime
            broadcast(new HonorEventUpdated($honorEvent));

            return redirect()->route('admin.honor.index')
                ->with('success', 'Đợt vote đã được cập nhật thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật đợt vote.');
        }
    }

    /**
     * Bật/tắt đợt vote
     */
    public function toggleStatus(HonorEvent $honorEvent)
    {
        try {
            $honorEvent->update(['is_active' => ! $honorEvent->is_active]);

            // Broadcast event updated realtime
            broadcast(new HonorEventUpdated($honorEvent));

            $status = $honorEvent->is_active ? 'bật' : 'tắt';

            return redirect()->back()
                ->with('success', "Đợt vote đã được {$status} thành công!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi thay đổi trạng thái.');
        }
    }

    /**
     * Reset tất cả vote trong đợt
     */
    public function resetVotes(HonorEvent $honorEvent)
    {
        try {
            DB::beginTransaction();

            HonorVote::where('honor_event_id', $honorEvent->id)->delete();

            DB::commit();

            // Broadcast votes reset realtime
            broadcast(new HonorVotesReset($honorEvent));

            return redirect()->back()
                ->with('success', 'Tất cả vote đã được reset thành công!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi reset vote.');
        }
    }

    /**
     * Xóa đợt vote
     */
    public function destroy(HonorEvent $honorEvent)
    {
        try {
            DB::beginTransaction();

            $eventId = $honorEvent->id;
            $eventName = $honorEvent->name;

            // Xóa tất cả vote trước
            HonorVote::where('honor_event_id', $honorEvent->id)->delete();

            // Xóa đợt vote
            $honorEvent->delete();

            DB::commit();

            // Broadcast event deleted realtime
            broadcast(new HonorEventDeleted($eventId, $eventName));

            return redirect()->route('admin.honor.index')
                ->with('success', 'Đợt vote đã được xóa thành công!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa đợt vote.');
        }
    }

    /**
     * Lấy thống kê vote theo role
     */
    private function getVotesByRole(HonorEvent $honorEvent)
    {
        return HonorVote::where('honor_event_id', $honorEvent->id)
            ->select('voter_role')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(weight) as total_weight')
            ->groupBy('voter_role')
            ->get();
    }

    /**
     * Lấy top items được vote nhiều nhất
     */
    private function getTopVotedItems(HonorEvent $honorEvent)
    {
        return HonorVote::where('honor_event_id', $honorEvent->id)
            ->select('voted_item_id', 'vote_type')
            ->selectRaw('SUM(weight) as total_weight')
            ->selectRaw('COUNT(*) as vote_count')
            ->groupBy('voted_item_id', 'vote_type')
            ->orderBy('total_weight', 'desc')
            ->limit(10)
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
     * Lấy đối tượng vote theo ID
     */
    private function getVotedItemById(string $type, int $id)
    {
        switch ($type) {
            case 'user':
                return User::with('profile')->find($id);
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
     * Lấy tên đối tượng vote
     */
    private function getItemName($item, string $type): string
    {
        switch ($type) {
            case 'user':
                return $item->name ?? $item->display_name ?? 'Unknown User';
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
