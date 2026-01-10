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
 * Uses slug for better security (harder to guess than sequential IDs)
 */
Broadcast::channel('conversation.{slug}', function ($user, $slug) {
    $conversation = ChatConversation::where('slug', $slug)->first();

    if (! $conversation) {
        return false;
    }

    return $conversation->hasParticipant($user->id);
});

/**
 * Presence channel for online users in a conversation
 * Uses slug for better security
 */
Broadcast::channel('conversation.{slug}.presence', function ($user, $slug) {
    $conversation = ChatConversation::where('slug', $slug)->first();

    if (! $conversation || ! $conversation->hasParticipant($user->id)) {
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

/**
 * Public channel for honor events
 * Anyone can listen to honor event updates
 */
Broadcast::channel('honor', function () {
    return true;
});

/**
 * Public channel for specific honor event
 * Anyone can listen to vote updates for a specific event
 */
Broadcast::channel('honor.event.{eventId}', function () {
    return true;
});
