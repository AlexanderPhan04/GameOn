<?php

namespace App\Http\Controllers;

use App\Events\NewFollowerNotification;
use App\Models\Follow;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Show the following page with followers, following, and suggestions.
     */
    public function index()
    {
        $currentUser = Auth::user();

        // Get users the current user follows
        $following = $currentUser->following()
            ->with('profile')
            ->orderBy('follows.created_at', 'desc')
            ->get();

        // Get users who follow the current user
        $followers = $currentUser->followers()
            ->with('profile')
            ->orderBy('follows.created_at', 'desc')
            ->get();

        // Get IDs of users already following
        $followingIds = $following->pluck('id')->toArray();
        $followingIds[] = $currentUser->id; // Exclude self

        // Get suggestions (users not yet followed, excluding self)
        $suggestions = User::where('status', 'active')
            ->whereNotIn('id', $followingIds)
            ->with('profile')
            ->withCount('followers')
            ->orderBy('followers_count', 'desc')
            ->take(20)
            ->get();

        return view('follow.index', compact('following', 'followers', 'suggestions'));
    }

    /**
     * Toggle follow/unfollow a user.
     */
    public function toggle(User $user): JsonResponse
    {
        $currentUser = Auth::user();

        // Can't follow yourself
        if ($currentUser->id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể theo dõi chính mình.',
            ], 400);
        }

        $existingFollow = Follow::where('follower_id', $currentUser->id)
            ->where('following_id', $user->id)
            ->first();

        if ($existingFollow) {
            // Unfollow
            $existingFollow->delete();

            return response()->json([
                'success' => true,
                'is_following' => false,
                'message' => 'Đã hủy theo dõi.',
                'followers_count' => $user->followers()->count(),
            ]);
        } else {
            // Follow
            Follow::create([
                'follower_id' => $currentUser->id,
                'following_id' => $user->id,
            ]);

            // Create notification for the user being followed
            $notification = Notification::createFollowNotification($user->id, [
                'id' => $currentUser->id,
                'name' => $currentUser->profile?->full_name ?: $currentUser->name,
                'avatar' => $currentUser->getDisplayAvatar(),
            ]);

            // Broadcast real-time notification
            broadcast(new NewFollowerNotification($user->id, $currentUser, $notification->id));

            return response()->json([
                'success' => true,
                'is_following' => true,
                'message' => 'Đã theo dõi.',
                'followers_count' => $user->followers()->count(),
            ]);
        }
    }


    /**
     * Get follow status for a user.
     */
    public function status(User $user): JsonResponse
    {
        $currentUser = Auth::user();

        if (!$currentUser) {
            return response()->json([
                'success' => true,
                'is_following' => false,
                'followers_count' => $user->followers()->count(),
                'following_count' => $user->following()->count(),
            ]);
        }

        $isFollowing = Follow::where('follower_id', $currentUser->id)
            ->where('following_id', $user->id)
            ->exists();

        return response()->json([
            'success' => true,
            'is_following' => $isFollowing,
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
        ]);
    }

    /**
     * Get followers list of a user.
     */
    public function followers(User $user): JsonResponse
    {
        $followers = $user->followers()
            ->with('profile')
            ->orderBy('follows.created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'followers' => $followers,
        ]);
    }

    /**
     * Get following list of a user.
     */
    public function following(User $user): JsonResponse
    {
        $following = $user->following()
            ->with('profile')
            ->orderBy('follows.created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'following' => $following,
        ]);
    }
}
