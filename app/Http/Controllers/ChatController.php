<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display chat interface
     */
    public function index()
    {
        $user = Auth::user();

        // Get conversations directly using query builder
        $conversations = ChatConversation::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['participants.user'])
            ->orderBy('last_message_at', 'desc')
            ->limit(20)
            ->get();

        return view('chat.index', compact('conversations'));
    }

    /**
     * Show specific conversation
     */
    public function show(ChatConversation $conversation)
    {
        $user = Auth::user();

        // Check if user is participant
        if (! $conversation->hasParticipant($user->id)) {
            abort(403, 'You are not a participant in this conversation');
        }

        // Mark as read
        $conversation->markAsReadForUser($user->id);

        // Get messages with pagination
        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        return view('chat.conversation', compact('conversation', 'messages', 'user'));
    }

    /**
     * Start new conversation or get existing one
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

        $conversation = ChatConversation::createOrGetPrivate($currentUser->id, $otherUserId);

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
        $user = Auth::user();

        // Check if user is participant and not blocked
        $participant = $conversation->participants()
            ->where('user_id', $user->id)
            ->first();

        if (! $participant || $participant->is_blocked || $participant->isMuted()) {
            return response()->json(['error' => 'You cannot send messages in this conversation'], 403);
        }

        $request->validate([
            'content' => 'nullable|string|max:5000',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt',
            'type' => 'in:text,image,file',
        ]);

        DB::beginTransaction();
        try {
            $messageData = [
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'type' => $request->type ?? 'text',
                'content' => $request->content,
            ];

            // Handle file attachment
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $path = $file->store('chat', 'public');

                $messageData['attachment_name'] = $file->getClientOriginalName();
                $messageData['attachment_path'] = $path;
                $messageData['attachment_type'] = $file->getClientOriginalExtension();
                $messageData['attachment_size'] = $file->getSize();

                if (in_array($messageData['attachment_type'], ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $messageData['type'] = 'image';
                } else {
                    $messageData['type'] = 'file';
                }
            }

            $message = ChatMessage::create($messageData);

            // Update conversation last message time
            $conversation->update(['last_message_at' => now()]);

            DB::commit();

            // Load the message with sender for response
            $message->load('sender');

            return response()->json([
                'success' => true,
                'message' => [
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
                    'attachment_path' => $message->attachment_path,
                    'formatted_time' => $message->formatted_time,
                    'created_at' => $message->created_at->toISOString(),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }

    /**
     * Search users for starting conversation
     */
    public function searchUsers(Request $request)
    {
        $query = $request->get('q', '');
        $users = User::searchForChat($query, Auth::id());

        return response()->json([
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->getDisplayAvatar(),
                    'online_status' => $user->online_status,
                    'user_role' => $user->user_role,
                ];
            }),
        ]);
    }

    /**
     * Add reaction to message
     */
    public function addReaction(Request $request, ChatMessage $message)
    {
        $request->validate([
            'emoji' => 'required|string|max:10',
        ]);

        $user = Auth::user();

        // Check if user can access this message
        if (! $message->conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $message->addReaction($user->id, $request->emoji);

        return response()->json([
            'success' => true,
            'reactions' => $message->reactions,
        ]);
    }

    /**
     * Toggle reaction to message
     */
    public function toggleReaction(Request $request, ChatMessage $message)
    {
        $request->validate([
            'emoji' => 'required|string|max:10',
        ]);

        $user = Auth::user();

        // Check if user can access this message
        if (! $message->conversation->hasParticipant($user->id)) {
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
     * Lưu ý: Nên dùng Redis hoặc in-memory cache thay vì database
     */
    public function updateTypingStatus(Request $request, ChatConversation $conversation)
    {
        $user = Auth::user();

        if (! $conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $isTyping = $request->boolean('is_typing');

        // Update user activity (tạm thời, nên dùng Redis sau)
        $user->activity()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'is_typing' => $isTyping,
                'typing_started_at' => $isTyping ? now() : null,
            ]
        );

        // TODO: Nên dùng Redis để lưu typing status theo conversation_id
        // Cache::put("typing:{$conversation->id}:{$user->id}", $isTyping, 5);

        return response()->json(['success' => true]);
    }

    /**
     * Get typing users in conversation
     * Lưu ý: Nên dùng Redis để lưu typing status theo conversation_id
     */
    public function getTypingUsers(ChatConversation $conversation)
    {
        $user = Auth::user();

        if (! $conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        // TODO: Nên dùng Redis để lưu typing status theo conversation_id
        // Tạm thời lấy từ user_activities (không lý tưởng vì không có conversation_id)
        $participants = $conversation->participants()
            ->where('user_id', '!=', $user->id)
            ->with(['user.activity'])
            ->get();

        $typingUsers = $participants->filter(function ($participant) {
            return $participant->user->activity 
                && $participant->user->activity->is_typing 
                && $participant->user->activity->typing_started_at 
                && $participant->user->activity->typing_started_at->gt(now()->subSeconds(5));
        })->map(function ($participant) {
            return [
                'id' => $participant->user->id,
                'name' => $participant->user->name,
            ];
        });

        return response()->json([
            'typing_users' => $typingUsers,
        ]);
    }

    /**
     * Block/unblock user in conversation
     */
    public function toggleBlock(Request $request, ChatConversation $conversation)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $currentUser = Auth::user();
        $targetUserId = $request->user_id;

        // Check if current user is participant
        if (! $conversation->hasParticipant($currentUser->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        // Get target user's participant record
        $targetParticipant = $conversation->participants()
            ->where('user_id', $targetUserId)
            ->first();

        if (! $targetParticipant) {
            return response()->json(['error' => 'User is not in this conversation'], 404);
        }

        $isCurrentlyBlocked = $targetParticipant->is_blocked;
        $targetParticipant->update(['is_blocked' => ! $isCurrentlyBlocked]);

        return response()->json([
            'success' => true,
            'is_blocked' => ! $isCurrentlyBlocked,
            'message' => ! $isCurrentlyBlocked ? 'User blocked successfully' : 'User unblocked successfully',
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

        if (! $participant) {
            return response()->json(['error' => 'You are not in this conversation'], 404);
        }

        $participant->delete();

        return response()->json([
            'success' => true,
            'message' => 'You have left the conversation',
        ]);
    }

    /**
     * Delete message (soft delete)
     */
    public function deleteMessage(ChatMessage $message)
    {
        $user = Auth::user();

        // Only sender can delete their message
        if ($message->sender_id !== $user->id) {
            return response()->json(['error' => 'You can only delete your own messages'], 403);
        }

        $message->update([
            'is_deleted' => true,
            'deleted_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully',
        ]);
    }

    /**
     * Edit message
     */
    public function editMessage(Request $request, ChatMessage $message)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $user = Auth::user();

        // Only sender can edit their message
        if ($message->sender_id !== $user->id) {
            return response()->json(['error' => 'You can only edit your own messages'], 403);
        }

        // Check if message is too old to edit (e.g., 15 minutes)
        if ($message->created_at->diffInMinutes(now()) > 15) {
            return response()->json(['error' => 'Message is too old to edit'], 403);
        }

        $message->update([
            'content' => $request->content,
            'is_edited' => true,
            'edited_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'content' => $message->content,
                'is_edited' => true,
                'edited_at' => $message->edited_at->toISOString(),
            ],
        ]);
    }

    /**
     * Get conversation messages via AJAX
     */
    public function getMessages(Request $request, ChatConversation $conversation)
    {
        $user = Auth::user();

        if (! $conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $afterId = $request->get('after_id');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 30);

        $query = $conversation->messages()
            ->with('sender')
            ->where('is_deleted', false);

        // If after_id is provided, get messages after that ID (for real-time updates)
        if ($afterId) {
            $query->where('id', '>', $afterId);
            $messages = $query->orderBy('created_at', 'asc')->limit($perPage)->get();
            $hasMore = false;
            $currentPage = 1;

            // If no new messages found, return empty success response instead of 404
            if ($messages->isEmpty()) {
                return response()->json([
                    'data' => [],
                    'has_more' => false,
                    'current_page' => 1,
                    'next_page_url' => null,
                ]);
            }
        } else {
            // Normal pagination for loading older messages
            $messages = $query->orderBy('created_at', 'asc')
                ->paginate($perPage, ['*'], 'page', $page);
            $hasMore = $messages->hasMorePages();
            $currentPage = $messages->currentPage();
            $messages = $messages->getCollection();
        }

        $formattedMessages = collect($messages)->map(function ($message) {
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
            'has_more' => $hasMore,
            'current_page' => $currentPage,
            'next_page_url' => $hasMore ? url()->current().'?page='.($currentPage + 1) : null,
        ]);
    }

    /**
     * Report inappropriate content
     */
    public function reportMessage(Request $request, ChatMessage $message)
    {
        $request->validate([
            'reason' => 'required|string|in:spam,harassment,inappropriate,other',
            'description' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        // Check if user can access this message
        if (! $message->conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        // Here you would typically store the report in a reports table
        // For now, we'll just return success
        // You might want to create a MessageReport model and table

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
        // For now, just count all active users
        // Later you can implement proper online tracking
        $count = User::where('status', 'active')->count();

        return response()->json(['online_count' => $count]);
    }

    /**
     * Mark conversation as read
     */
    public function markAsRead(ChatConversation $conversation)
    {
        $user = Auth::user();

        if (! $conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $conversation->markAsReadForUser($user->id);

        return response()->json(['success' => true]);
    }

    /**
     * Create group conversation
     */
    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'user_ids' => 'required|array|min:2|max:50',
            'user_ids.*' => 'exists:users,id',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        $userIds = $request->user_ids;

        // Add current user to the list if not included
        if (! in_array($user->id, $userIds)) {
            $userIds[] = $user->id;
        }

        DB::beginTransaction();
        try {
            $conversationData = [
                'type' => 'group',
                'name' => $request->name,
                'description' => $request->description,
                'created_by' => $user->id,
                'is_active' => true,
            ];

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $path = $avatar->store('chat/avatars', 'public');
                $conversationData['avatar'] = $path;
            }

            $conversation = ChatConversation::create($conversationData);

            // Add participants
            foreach ($userIds as $userId) {
                $conversation->participants()->create([
                    'user_id' => $userId,
                    'role' => $userId == $user->id ? 'admin' : 'member',
                    'joined_at' => now(),
                ]);
            }

            // Send system message
            $conversation->messages()->create([
                'type' => 'system',
                'content' => $user->name.' đã tạo nhóm chat "'.$request->name.'"',
                'sender_id' => $user->id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'conversation_id' => $conversation->id,
                'redirect_url' => route('chat.show', $conversation),
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error' => 'Không thể tạo nhóm chat'], 500);
        }
    }

    /**
     * Delete conversation (only for group admin or private chat participants)
     */
    public function deleteConversation(ChatConversation $conversation)
    {
        $user = Auth::user();

        // Check permissions
        if ($conversation->type === 'group') {
            // Only group admin can delete group chat
            $participant = $conversation->participants()
                ->where('user_id', $user->id)
                ->first();

            if (! $participant || $participant->role !== 'admin') {
                return response()->json(['error' => 'Chỉ admin nhóm mới có thể xóa cuộc trò chuyện'], 403);
            }
        } else {
            // For private chat, only participants can delete
            if (! $conversation->hasParticipant($user->id)) {
                return response()->json(['error' => 'Bạn không có quyền xóa cuộc trò chuyện này'], 403);
            }
        }

        DB::beginTransaction();
        try {
            // Soft delete all messages
            $conversation->messages()->update(['is_deleted' => true, 'deleted_at' => now()]);

            // Remove all participants
            $conversation->participants()->delete();

            // Soft delete conversation
            $conversation->update(['is_active' => false]);
            $conversation->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cuộc trò chuyện đã được xóa',
                'redirect_url' => route('chat.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error' => 'Không thể xóa cuộc trò chuyện'], 500);
        }
    }

    /**
     * Clear chat history (delete all messages)
     */
    public function clearHistory(ChatConversation $conversation)
    {
        $user = Auth::user();

        if (! $conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Bạn không có quyền xóa lịch sử chat'], 403);
        }

        DB::beginTransaction();
        try {
            // Mark all messages as deleted
            $conversation->messages()->update([
                'is_deleted' => true,
                'deleted_at' => now(),
            ]);

            // Do not append a system message when clearing history (silent clear per requirements)

            DB::commit();

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error' => 'Không thể xóa lịch sử chat'], 500);
        }
    }

    /**
     * Update user heartbeat (online status)
     */
    public function heartbeat(Request $request)
    {
        $user = Auth::user();

        // Update user's last activity timestamp
        $user->update(['last_activity_at' => now()]);

        // Store conversation_id if provided for more specific tracking
        $conversationId = $request->get('conversation_id');

        return response()->json([
            'success' => true,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Get participants online status for a conversation
     */
    public function getParticipantsStatus(ChatConversation $conversation)
    {
        $user = Auth::user();

        if (! $conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        // Get all participants except current user
        $participants = $conversation->participants()
            ->with('user')
            ->where('user_id', '!=', $user->id)
            ->get()
            ->map(function ($participant) {
                $user = $participant->user;
                $isOnline = $user->last_activity_at &&
                           $user->last_activity_at->diffInMinutes(now()) <= 5; // Online if active within 5 minutes

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->getDisplayAvatar(),
                    'is_online' => $isOnline,
                    'last_activity' => $user->last_activity_at ? $user->last_activity_at->toISOString() : null,
                    'role' => $participant->role,
                ];
            });

        return response()->json([
            'participants' => $participants,
            'total_count' => $participants->count(),
            'online_count' => $participants->where('is_online', true)->count(),
        ]);
    }
}
