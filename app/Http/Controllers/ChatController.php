<?php

namespace App\Http\Controllers;

use App\Events\UserTyping;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ChatController - HTTP layer for chat functionality
 * Business logic delegated to ChatService
 * Refactored for proper MVC architecture
 */
class ChatController extends Controller
{
    protected ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Display chat interface
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login');
        }

        $conversations = $this->chatService->getUserConversations($user);

        return view('chat.index', compact('conversations'));
    }

    /**
     * Show specific conversation
     */
    public function show(ChatConversation $conversation)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login');
        }

        if (!$conversation->hasParticipant($user->id)) {
            return redirect()->route('chat.index')
                ->with('error', 'Bạn không phải là thành viên của cuộc trò chuyện này.');
        }

        $conversation->markAsReadForUser($user->id);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        return view('chat.conversation', compact('conversation', 'messages', 'user'));
    }

    /**
     * Start new conversation
     */
    public function startConversation(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $currentUser = Auth::user();
        $otherUserId = $request->user_id;

        if ($currentUser->id == $otherUserId) {
            return response()->json(['error' => 'Cannot start conversation with yourself'], 400);
        }

        // Check if user is restricted - can only chat with admin/super_admin
        if (in_array($currentUser->status, ['suspended', 'banned', 'deleted'])) {
            $otherUser = User::find($otherUserId);
            if (!in_array($otherUser->user_role, ['admin', 'super_admin'])) {
                return response()->json([
                    'error' => 'Tài khoản của bạn đang bị hạn chế. Bạn chỉ có thể chat với quản trị viên.',
                ], 403);
            }
        }

        $conversation = $this->chatService->getOrCreatePrivateConversation(
            $currentUser->id,
            $otherUserId
        );

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'redirect_url' => route('chat.show', $conversation),
        ]);
    }

    /**
     * Send message
     */
    public function sendMessage(Request $request, ChatConversation $conversation)
    {
        $request->validate([
            'content' => 'nullable|string|max:5000',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt',
            'type' => 'in:text,image,file',
        ]);

        $user = Auth::user();
        $attachment = $request->hasFile('attachment') ? $request->file('attachment') : null;

        $result = $this->chatService->sendMessage(
            $conversation,
            $user,
            $request->only(['content', 'type']),
            $attachment
        );

        if (!$result['success']) {
            return response()->json(['error' => $result['message']], 403);
        }

        $message = $result['message'];

        return response()->json([
            'success' => true,
            'message' => $this->chatService->formatMessageForResponse($message),
        ]);
    }

    /**
     * Search users
     */
    public function searchUsers(Request $request)
    {
        $query = $request->get('q', '');
        $users = User::searchForChat($query, Auth::id());

        return response()->json([
            'users' => $users->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->getDisplayAvatar(),
                'user_role' => $user->user_role,
            ]),
        ]);
    }

    /**
     * Add reaction
     */
    public function addReaction(Request $request, ChatMessage $message)
    {
        $request->validate(['emoji' => 'required|string|max:10']);

        $user = Auth::user();

        if (!$message->conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $message->addReaction($user->id, $request->emoji);

        return response()->json([
            'success' => true,
            'reactions' => $message->reactions,
        ]);
    }

    /**
     * Toggle reaction
     */
    public function toggleReaction(Request $request, ChatMessage $message)
    {
        $request->validate(['emoji' => 'required|string|max:10']);

        $user = Auth::user();

        if (!$message->conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $message->toggleReaction($user->id, $request->emoji);

        return response()->json([
            'success' => true,
            'reactions' => $message->reactions,
        ]);
    }

    /**
     * Update typing status
     */
    public function updateTypingStatus(Request $request, ChatConversation $conversation)
    {
        $user = Auth::user();

        if (!$conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        broadcast(new UserTyping($conversation->id, $user, $request->boolean('is_typing')))->toOthers();

        return response()->json(['success' => true]);
    }

    /**
     * Get typing users
     */
    public function getTypingUsers(ChatConversation $conversation)
    {
        $user = Auth::user();

        if (!$conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $participants = $conversation->participants()
            ->where('user_id', '!=', $user->id)
            ->with(['user.activity'])
            ->get();

        $typingUsers = $participants->filter(function ($participant) {
            return $participant->user->activity
                && $participant->user->activity->is_typing
                && $participant->user->activity->typing_started_at
                && $participant->user->activity->typing_started_at->gt(now()->subSeconds(5));
        })->map(fn($p) => ['id' => $p->user->id, 'name' => $p->user->name]);

        return response()->json(['typing_users' => $typingUsers]);
    }

    /**
     * Toggle block
     */
    public function toggleBlock(Request $request, ChatConversation $conversation)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $currentUser = Auth::user();

        if (!$conversation->hasParticipant($currentUser->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $targetParticipant = $conversation->participants()
            ->where('user_id', $request->user_id)
            ->first();

        if (!$targetParticipant) {
            return response()->json(['error' => 'User is not in this conversation'], 404);
        }

        $isCurrentlyBlocked = $targetParticipant->is_blocked;
        $targetParticipant->update(['is_blocked' => !$isCurrentlyBlocked]);

        return response()->json([
            'success' => true,
            'is_blocked' => !$isCurrentlyBlocked,
            'message' => !$isCurrentlyBlocked ? 'User blocked' : 'User unblocked',
        ]);
    }

    /**
     * Leave conversation
     */
    public function leaveConversation(ChatConversation $conversation)
    {
        $user = Auth::user();

        $participant = $conversation->participants()
            ->where('user_id', $user->id)
            ->first();

        if (!$participant) {
            return response()->json(['error' => 'You are not in this conversation'], 404);
        }

        $participant->delete();

        return response()->json([
            'success' => true,
            'message' => 'You have left the conversation',
        ]);
    }

    /**
     * Delete message
     */
    public function deleteMessage(ChatMessage $message)
    {
        $result = $this->chatService->deleteMessage($message, Auth::user());

        if (!$result['success']) {
            return response()->json(['error' => $result['message']], 403);
        }

        return response()->json($result);
    }

    /**
     * Edit message
     */
    public function editMessage(Request $request, ChatMessage $message)
    {
        $request->validate(['content' => 'required|string|max:5000']);

        $result = $this->chatService->editMessage($message, Auth::user(), $request->content);

        if (!$result['success']) {
            return response()->json(['error' => $result['message']], 403);
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $result['message']->id,
                'content' => $result['message']->content,
                'is_edited' => true,
                'edited_at' => $result['message']->edited_at->toISOString(),
            ],
        ]);
    }

    /**
     * Get messages
     */
    public function getMessages(Request $request, ChatConversation $conversation)
    {
        $user = Auth::user();

        if (!$conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $result = $this->chatService->getMessages(
            $conversation,
            $request->get('after_id'),
            $request->get('page', 1),
            $request->get('per_page', 30)
        );

        $formattedMessages = $result['messages']->map(function ($message) {
            return [
                'id' => $message->id,
                'content' => $message->content,
                'type' => $message->type,
                'sender' => [
                    'id' => $message->sender->id,
                    'name' => $message->sender->name,
                    'avatar' => $message->sender->getDisplayAvatar(),
                ],
                'attachment_url' => $message->attachment_url,
                'attachment_name' => $message->attachment_name,
                'reactions' => $message->reactions,
                'is_edited' => $message->is_edited,
                'formatted_time' => $message->formatted_time,
                'created_at' => $message->created_at->toISOString(),
            ];
        });

        return response()->json([
            'data' => $formattedMessages,
            'has_more' => $result['has_more'],
            'current_page' => $result['current_page'],
            'next_page_url' => $result['has_more']
                ? url()->current() . '?page=' . ($result['current_page'] + 1)
                : null,
        ]);
    }

    /**
     * Report message
     */
    public function reportMessage(Request $request, ChatMessage $message)
    {
        $request->validate([
            'reason' => 'required|string|in:spam,harassment,inappropriate,other',
            'description' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        if (!$message->conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Message reported successfully. Our team will review it.',
        ]);
    }

    /**
     * Get online users count
     */
    public function getOnlineUsersCount()
    {
        $count = User::where('status', 'active')->count();
        return response()->json(['online_count' => $count]);
    }

    /**
     * Mark as read
     */
    public function markAsRead(ChatConversation $conversation)
    {
        $user = Auth::user();

        if (!$conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $conversation->markAsReadForUser($user->id);

        return response()->json(['success' => true]);
    }

    /**
     * Create group
     */
    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'user_ids' => 'nullable|array|max:50',
            'user_ids.*' => 'exists:users,id',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $avatar = $request->hasFile('avatar') ? $request->file('avatar') : null;

        $result = $this->chatService->createGroup(
            Auth::user(),
            $request->only(['name', 'description', 'user_ids']),
            $avatar
        );

        if (!$result['success']) {
            return response()->json(['success' => false, 'message' => $result['message']], 422);
        }

        return response()->json([
            'success' => true,
            'conversation_id' => $result['conversation']->id,
            'redirect_url' => route('chat.show', $result['conversation']),
        ]);
    }

    /**
     * Delete conversation
     */
    public function deleteConversation(ChatConversation $conversation)
    {
        $result = $this->chatService->deleteConversation($conversation, Auth::user());

        if (!$result['success']) {
            return response()->json(['error' => $result['message']], 403);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'redirect_url' => route('chat.index'),
        ]);
    }

    /**
     * Clear history
     */
    public function clearHistory(ChatConversation $conversation)
    {
        $user = Auth::user();

        if (!$conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Bạn không có quyền xóa lịch sử chat'], 403);
        }

        $result = $this->chatService->clearHistory($conversation);

        if (!$result['success']) {
            return response()->json(['error' => $result['message']], 500);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Heartbeat
     */
    public function heartbeat(Request $request)
    {
        Auth::user()->update(['last_activity_at' => now()]);

        return response()->json([
            'success' => true,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Get participants status
     */
    public function getParticipantsStatus(ChatConversation $conversation)
    {
        $user = Auth::user();

        if (!$conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $participants = $conversation->participants()
            ->with('user')
            ->where('user_id', '!=', $user->id)
            ->get()
            ->map(function ($participant) {
                $u = $participant->user;
                $isOnline = $u->last_activity_at &&
                    $u->last_activity_at->diffInMinutes(now()) <= 5;

                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'avatar' => $u->getDisplayAvatar(),
                    'is_online' => $isOnline,
                    'last_activity' => $u->last_activity_at?->toISOString(),
                    'role' => $participant->role,
                ];
            });

        return response()->json([
            'participants' => $participants,
            'total_count' => $participants->count(),
            'online_count' => $participants->where('is_online', true)->count(),
        ]);
    }

    /**
     * Get conversations for widget (API)
     */
    public function getConversations()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $conversations = $this->chatService->getUserConversations($user);
        
        $totalUnread = 0;
        $formattedConversations = $conversations->map(function ($conv) use ($user, &$totalUnread) {
            $otherParticipant = $conv->participants
                ->where('user_id', '!=', $user->id)
                ->first();
            
            $otherUser = $otherParticipant?->user;
            $unreadCount = $conv->getUnreadCount($user->id);
            $totalUnread += $unreadCount;
            
            $isOnline = $otherUser && $otherUser->last_activity_at &&
                $otherUser->last_activity_at->diffInMinutes(now()) <= 5;

            return [
                'id' => $conv->slug,
                'name' => $conv->type === 'private' 
                    ? ($otherUser?->display_name ?? $otherUser?->name ?? 'Unknown')
                    : $conv->name,
                'avatar' => $conv->getDisplayAvatar($user->id),
                'last_message' => $conv->last_message_preview ?? null,
                'time' => $conv->last_message_at?->diffForHumans(null, true) ?? '',
                'unread' => $unreadCount > 0,
                'online' => $isOnline,
            ];
        });

        return response()->json([
            'success' => true,
            'conversations' => $formattedConversations,
            'unread_count' => $totalUnread,
        ]);
    }

    /**
     * Get unread count for widget
     */
    public function getUnreadCount()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['count' => 0]);
        }

        $count = ChatConversation::whereHas('participants', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get()->sum(function ($conv) use ($user) {
            return $conv->getUnreadCount($user->id);
        });

        return response()->json(['count' => $count]);
    }

    /**
     * Get messages for widget (simplified)
     */
    public function getMessagesForWidget(Request $request, ChatConversation $conversation)
    {
        $user = Auth::user();

        if (!$conversation->hasParticipant($user->id)) {
            return response()->json(['success' => false, 'error' => 'Access denied'], 403);
        }

        $limit = $request->get('limit', 20);

        // Query messages directly without relationship ordering conflict
        $messages = ChatMessage::where('conversation_id', $conversation->id)
            ->whereNull('deleted_at')
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();

        $formattedMessages = $messages->map(function ($msg) use ($user) {
            return [
                'id' => $msg->id,
                'content' => $msg->content,
                'is_mine' => $msg->sender_id === $user->id,
                'time' => $msg->created_at->format('H:i'),
            ];
        });

        // Mark as read
        $conversation->markAsReadForUser($user->id);

        return response()->json([
            'success' => true,
            'messages' => $formattedMessages,
        ]);
    }

    /**
     * Send message from widget (simplified)
     */
    public function sendFromWidget(Request $request, ChatConversation $conversation)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $user = Auth::user();

        if (!$conversation->hasParticipant($user->id)) {
            return response()->json(['success' => false, 'error' => 'Access denied'], 403);
        }

        $result = $this->chatService->sendMessage(
            $conversation,
            $user,
            ['content' => $request->message, 'type' => 'text'],
            null
        );

        if (!$result['success']) {
            return response()->json(['success' => false, 'error' => $result['message']], 403);
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $result['message']->id,
                'content' => $result['message']->content,
                'is_mine' => true,
                'time' => $result['message']->created_at->format('H:i'),
            ],
        ]);
    }
}
