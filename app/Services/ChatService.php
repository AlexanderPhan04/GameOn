<?php

namespace App\Services;

use App\Events\MessageDeleted;
use App\Events\MessageSent;
use App\Events\NewChatNotification;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * ChatService - Chat business logic
 * Extracted from ChatController for proper MVC architecture
 */
class ChatService
{
    /**
     * Get user's conversations
     *
     * @param User $user
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserConversations(User $user, int $limit = 20)
    {
        return ChatConversation::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['participants.user'])
            ->orderByRaw('last_message_at IS NULL, last_message_at DESC')
            ->limit($limit)
            ->get();
    }

    /**
     * Create or get private conversation between two users
     *
     * @param int $userId1
     * @param int $userId2
     * @return ChatConversation
     */
    public function getOrCreatePrivateConversation(int $userId1, int $userId2): ChatConversation
    {
        return ChatConversation::createOrGetPrivate($userId1, $userId2);
    }

    /**
     * Send a message in a conversation
     *
     * @param ChatConversation $conversation
     * @param User $sender
     * @param array $data
     * @param UploadedFile|null $attachment
     * @return array
     */
    public function sendMessage(
        ChatConversation $conversation,
        User $sender,
        array $data,
        ?UploadedFile $attachment = null
    ): array {
        // Check if user is restricted (suspended/banned)
        if (in_array($sender->status, ['suspended', 'banned', 'deleted'])) {
            // Check if conversation has admin/super_admin
            $hasAdmin = $conversation->participants()
                ->whereHas('user', function ($query) {
                    $query->whereIn('user_role', ['admin', 'super_admin']);
                })
                ->exists();
            
            if (!$hasAdmin) {
                return [
                    'success' => false,
                    'message' => 'Tài khoản của bạn đang bị hạn chế. Bạn chỉ có thể chat với quản trị viên.',
                ];
            }
        }

        // Check participant status
        $participant = $conversation->participants()
            ->where('user_id', $sender->id)
            ->first();

        if (!$participant || $participant->is_blocked || $participant->isMuted()) {
            return [
                'success' => false,
                'message' => 'You cannot send messages in this conversation',
            ];
        }

        DB::beginTransaction();
        try {
            $messageData = [
                'conversation_id' => $conversation->id,
                'sender_id' => $sender->id,
                'type' => $data['type'] ?? 'text',
                'content' => $data['content'] ?? null,
            ];

            // Handle file attachment
            if ($attachment) {
                $path = $attachment->store('chat', 'public');

                $messageData['attachment_name'] = $attachment->getClientOriginalName();
                $messageData['attachment_path'] = $path;
                $messageData['attachment_type'] = $attachment->getClientOriginalExtension();
                $messageData['attachment_size'] = $attachment->getSize();

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

            // Load sender for response
            $message->load('sender');

            // Broadcast message
            broadcast(new MessageSent($message))->toOthers();

            // Notify other participants
            $this->notifyParticipants($conversation, $sender, $message);

            return [
                'success' => true,
                'message' => $message,
            ];
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to send message: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to send message',
            ];
        }
    }

    /**
     * Notify other participants about new message
     */
    protected function notifyParticipants(ChatConversation $conversation, User $sender, ChatMessage $message): void
    {
        $otherParticipants = $conversation->participants()
            ->where('user_id', '!=', $sender->id)
            ->pluck('user_id');

        foreach ($otherParticipants as $participantId) {
            broadcast(new NewChatNotification($participantId, $message));
        }
    }

    /**
     * Create a group conversation
     *
     * @param User $creator
     * @param array $data
     * @param UploadedFile|null $avatar
     * @return array
     */
    public function createGroup(User $creator, array $data, ?UploadedFile $avatar = null): array
    {
        $userIds = $data['user_ids'] ?? [];

        // Add creator if not included
        if (!in_array($creator->id, $userIds)) {
            $userIds[] = $creator->id;
        }

        // Need at least 2 participants
        if (count($userIds) < 2) {
            return [
                'success' => false,
                'message' => 'Vui lòng thêm ít nhất 1 thành viên khác vào nhóm',
            ];
        }

        DB::beginTransaction();
        try {
            $conversationData = [
                'type' => 'group',
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'created_by' => $creator->id,
                'is_active' => true,
            ];

            // Handle avatar upload
            if ($avatar) {
                $path = $avatar->store('chat/avatars', 'public');
                $conversationData['avatar'] = $path;
            }

            $conversation = ChatConversation::create($conversationData);

            // Add participants
            foreach ($userIds as $userId) {
                $conversation->participants()->create([
                    'user_id' => $userId,
                    'role' => $userId == $creator->id ? 'admin' : 'member',
                    'joined_at' => now(),
                ]);
            }

            // Send system message
            $conversation->messages()->create([
                'type' => 'system',
                'content' => $creator->name . ' đã tạo nhóm chat "' . $data['name'] . '"',
                'sender_id' => $creator->id,
            ]);

            DB::commit();

            return [
                'success' => true,
                'conversation' => $conversation,
            ];
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to create group: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Không thể tạo nhóm chat',
            ];
        }
    }

    /**
     * Delete a conversation
     *
     * @param ChatConversation $conversation
     * @param User $user
     * @return array
     */
    public function deleteConversation(ChatConversation $conversation, User $user): array
    {
        // Check permissions
        if ($conversation->type === 'group') {
            $participant = $conversation->participants()
                ->where('user_id', $user->id)
                ->first();

            if (!$participant || $participant->role !== 'admin') {
                return [
                    'success' => false,
                    'message' => 'Chỉ admin nhóm mới có thể xóa cuộc trò chuyện',
                ];
            }
        } else {
            if (!$conversation->hasParticipant($user->id)) {
                return [
                    'success' => false,
                    'message' => 'Bạn không có quyền xóa cuộc trò chuyện này',
                ];
            }
        }

        DB::beginTransaction();
        try {
            // Soft delete messages
            $conversation->messages()->update(['deleted_at' => now()]);

            // Remove participants
            $conversation->participants()->delete();

            // Soft delete conversation
            $conversation->update(['is_active' => false]);
            $conversation->delete();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Cuộc trò chuyện đã được xóa',
            ];
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to delete conversation: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Không thể xóa cuộc trò chuyện',
            ];
        }
    }

    /**
     * Clear conversation history for a specific user
     * Only clears for the requesting user, not for everyone
     *
     * @param ChatConversation $conversation
     * @param User $user
     * @return array
     */
    public function clearHistory(ChatConversation $conversation, User $user): array
    {
        try {
            // Update cleared_at for this user's participant record
            $conversation->participants()
                ->where('user_id', $user->id)
                ->update(['cleared_at' => now()]);

            return ['success' => true];
        } catch (\Exception $e) {
            Log::error('Failed to clear history: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Không thể xóa lịch sử chat',
            ];
        }
    }

    /**
     * Delete a message
     *
     * @param ChatMessage $message
     * @param User $user
     * @return array
     */
    public function deleteMessage(ChatMessage $message, User $user): array
    {
        if ($message->sender_id !== $user->id) {
            return [
                'success' => false,
                'message' => 'You can only delete your own messages',
            ];
        }

        $conversationId = $message->conversation_id;
        $messageId = $message->id;

        $message->update(['deleted_at' => now()]);

        // Broadcast deletion
        broadcast(new MessageDeleted($conversationId, $messageId))->toOthers();

        return [
            'success' => true,
            'message' => 'Message deleted successfully',
        ];
    }

    /**
     * Edit a message
     *
     * @param ChatMessage $message
     * @param User $user
     * @param string $newContent
     * @return array
     */
    public function editMessage(ChatMessage $message, User $user, string $newContent): array
    {
        if ($message->sender_id !== $user->id) {
            return [
                'success' => false,
                'message' => 'You can only edit your own messages',
            ];
        }

        // Check if message is too old (15 minutes)
        if ($message->created_at->diffInMinutes(now()) > 15) {
            return [
                'success' => false,
                'message' => 'Message is too old to edit',
            ];
        }

        $message->update([
            'content' => $newContent,
            'is_edited' => true,
            'edited_at' => now(),
        ]);

        return [
            'success' => true,
            'message' => $message,
        ];
    }

    /**
     * Get conversation messages
     *
     * @param ChatConversation $conversation
     * @param int|null $afterId
     * @param int $page
     * @param int $perPage
     * @param \Carbon\Carbon|null $clearedAt
     * @return array
     */
    public function getMessages(
        ChatConversation $conversation,
        ?int $afterId = null,
        int $page = 1,
        int $perPage = 30,
        $clearedAt = null
    ): array {
        $query = $conversation->messages()
            ->with('sender')
            ->whereNull('deleted_at');

        // Filter messages after cleared_at if set
        if ($clearedAt) {
            $query->where('created_at', '>', $clearedAt);
        }

        if ($afterId) {
            $query->where('id', '>', $afterId);
            $messages = $query->orderBy('created_at', 'asc')->limit($perPage)->get();
            $hasMore = false;
            $currentPage = 1;
        } else {
            $paginated = $query->orderBy('created_at', 'asc')
                ->paginate($perPage, ['*'], 'page', $page);
            $hasMore = $paginated->hasMorePages();
            $currentPage = $paginated->currentPage();
            $messages = $paginated->getCollection();
        }

        return [
            'messages' => $messages,
            'has_more' => $hasMore,
            'current_page' => $currentPage,
        ];
    }

    /**
     * Format message for API response
     *
     * @param ChatMessage $message
     * @return array
     */
    public function formatMessageForResponse(ChatMessage $message): array
    {
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
            'attachment_path' => $message->attachment_path,
            'formatted_time' => $message->formatted_time,
            'created_at' => $message->created_at->toISOString(),
        ];
    }
}
