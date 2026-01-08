<?php

use App\Models\ChatConversation;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/**
 * Private channel for user notifications
 */
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

/**
 * Private channel for chat conversations
 * Only participants can listen to this channel
 */
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = ChatConversation::find($conversationId);
    
    if (!$conversation) {
        return false;
    }
    
    return $conversation->hasParticipant($user->id);
});

/**
 * Presence channel for online users in a conversation
 */
Broadcast::channel('conversation.{conversationId}.presence', function ($user, $conversationId) {
    $conversation = ChatConversation::find($conversationId);
    
    if (!$conversation || !$conversation->hasParticipant($user->id)) {
        return false;
    }
    
    return [
        'id' => $user->id,
        'name' => $user->name,
        'avatar' => $user->getDisplayAvatar(),
    ];
});

/**
 * Public channel for marketplace updates
 * Anyone can listen to product updates
 */
Broadcast::channel('marketplace', function () {
    return true;
});

/**
 * Private channel for team chat and notifications
 * All authenticated users can listen to this channel for realtime team updates
 * Chat functionality is still restricted by controller logic
 */
Broadcast::channel('team.{teamId}', function ($user, $teamId) {
    $team = \App\Models\Team::find($teamId);
    
    if (!$team) {
        return false;
    }
    
    // Allow all authenticated users to subscribe for realtime updates
    // This enables viewers of the team page to see member changes
    return true;
});
