<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostCommentLike;
use App\Models\PostCommentReaction;
use App\Models\PostLike;
use App\Models\PostReaction;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * PostService - Post business logic
 * Extracted from PostsController for proper MVC architecture
 */
class PostService
{
    /**
     * Create a new post
     *
     * @param User $user
     * @param array $data
     * @param array|null $files
     * @return Post
     */
    public function createPost(User $user, array $data, ?array $files = null): Post
    {
        // Determine root_post_id for share logic
        $rootId = null;
        if (!empty($data['shared_post_id'])) {
            $shared = Post::select('id', 'root_post_id')->find($data['shared_post_id']);
            if ($shared) {
                $rootId = $shared->root_post_id ?: $shared->id;
            }
        }

        $post = Post::create([
            'user_id' => $user->id,
            'content' => $data['content'] ?? null,
            'shared_post_id' => $data['shared_post_id'] ?? null,
            'root_post_id' => $rootId,
            'visibility' => $data['visibility'] ?? 'public',
            'visibility_include_ids' => $data['visibility_include_ids'] ?? null,
            'visibility_exclude_ids' => $data['visibility_exclude_ids'] ?? null,
        ]);

        // Save media files
        if ($files) {
            foreach ($files as $file) {
                $path = $file->store('posts', 'public');
                $type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
                $post->media()->create(['type' => $type, 'path' => $path]);
            }
        }

        // Increment shares_count on root post
        if (!empty($data['shared_post_id']) && $rootId) {
            Post::where('id', $rootId)->increment('shares_count');
        }

        return $post;
    }

    /**
     * Toggle like on a post
     *
     * @param Post $post
     * @param int $userId
     * @return bool
     */
    public function toggleLike(Post $post, int $userId): bool
    {
        $existing = PostLike::where(['post_id' => $post->id, 'user_id' => $userId])->first();

        if ($existing) {
            $existing->delete();
            if ($post->likes_count > 0) {
                $post->decrement('likes_count');
            }
            return false; // unliked
        }

        PostLike::create(['post_id' => $post->id, 'user_id' => $userId]);
        $post->increment('likes_count');
        return true; // liked
    }

    /**
     * React to a post
     *
     * @param Post $post
     * @param int $userId
     * @param string $type
     * @return array
     */
    public function react(Post $post, int $userId, string $type): array
    {
        $existing = PostReaction::where(['post_id' => $post->id, 'user_id' => $userId])->first();

        if ($type === 'none') {
            if ($existing) {
                $existing->delete();
                if ($post->likes_count > 0) {
                    $post->decrement('likes_count');
                }
            }
        } else {
            if ($existing) {
                if ($existing->type !== $type) {
                    $existing->update(['type' => $type]);
                }
            } else {
                PostReaction::create(['post_id' => $post->id, 'user_id' => $userId, 'type' => $type]);
                $post->increment('likes_count');
            }
        }

        $post->refresh();
        $topTypes = $post->reactions()
            ->select('type', DB::raw('count(*) as c'))
            ->groupBy('type')
            ->orderByDesc('c')
            ->limit(2)
            ->pluck('type')
            ->toArray();

        return [
            'success' => true,
            'topTypes' => $topTypes,
            'totalCount' => (int) $post->likes_count,
        ];
    }

    /**
     * Add a comment to a post
     *
     * @param Post $post
     * @param User $user
     * @param string $content
     * @param int|null $parentId
     * @return PostComment
     */
    public function addComment(Post $post, User $user, string $content, ?int $parentId = null): PostComment
    {
        $comment = PostComment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'parent_id' => $parentId,
            'content' => $content,
        ]);

        $post->increment('comments_count');

        return $comment;
    }

    /**
     * Delete a comment
     *
     * @param PostComment $comment
     * @param User $user
     * @return array
     */
    public function deleteComment(PostComment $comment, User $user): array
    {
        $isAdmin = in_array($user->user_role, ['admin', 'super_admin'], true);

        if ($comment->user_id !== $user->id && !$isAdmin) {
            return ['success' => false, 'message' => 'Permission denied'];
        }

        $comment->delete();

        return ['success' => true];
    }

    /**
     * React to a comment
     *
     * @param PostComment $comment
     * @param int $userId
     * @param string $type
     * @return void
     */
    public function reactToComment(PostComment $comment, int $userId, string $type): void
    {
        $existing = PostCommentReaction::where(['comment_id' => $comment->id, 'user_id' => $userId])->first();

        if ($type === 'none') {
            if ($existing) {
                $existing->delete();
                if ($comment->likes_count > 0) {
                    $comment->decrement('likes_count');
                }
            }
            return;
        }

        if ($existing) {
            if ($existing->type !== $type) {
                $existing->update(['type' => $type]);
            }
        } else {
            PostCommentReaction::create(['comment_id' => $comment->id, 'user_id' => $userId, 'type' => $type]);
            $comment->increment('likes_count');
        }
    }

    /**
     * Delete a post with all related data
     *
     * @param Post $post
     * @param User $user
     * @return array
     */
    public function deletePost(Post $post, User $user): array
    {
        $isAdmin = in_array($user->user_role, ['admin', 'super_admin'], true);

        if ($post->user_id !== $user->id && !$isAdmin) {
            return ['success' => false, 'message' => 'Permission denied'];
        }

        DB::transaction(function () use ($post) {
            // Delete media files
            foreach ($post->media as $media) {
                if ($media->path) {
                    try {
                        Storage::disk('public')->delete($media->path);
                    } catch (\Throwable $e) {
                        Log::warning('Failed to delete media: ' . $e->getMessage());
                    }
                }
            }

            $post->media()->delete();
            $post->likes()->delete();
            $post->comments()->delete();
            $post->reactions()->delete();
            $post->mentions()->delete();
            $post->delete();
        });

        return ['success' => true];
    }

    /**
     * Update a post
     *
     * @param Post $post
     * @param User $user
     * @param array $data
     * @param array|null $newFiles
     * @param array|null $deleteMediaIds
     * @return array
     */
    public function updatePost(
        Post $post,
        User $user,
        array $data,
        ?array $newFiles = null,
        ?array $deleteMediaIds = null
    ): array {
        $isAdmin = in_array($user->user_role, ['admin', 'super_admin'], true);

        if ($post->user_id !== $user->id && !$isAdmin) {
            return ['success' => false, 'message' => 'Permission denied'];
        }

        $post->update(['content' => $data['content'] ?? $post->content]);

        // Delete selected media
        if ($deleteMediaIds) {
            $mediaToDelete = $post->media()->whereIn('id', $deleteMediaIds)->get();
            foreach ($mediaToDelete as $media) {
                Storage::disk('public')->delete($media->path);
                $media->delete();
            }
        }

        // Add new media files
        if ($newFiles) {
            foreach ($newFiles as $file) {
                $path = $file->store('posts', 'public');
                $type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
                $post->media()->create(['type' => $type, 'path' => $path]);
            }
        }

        return ['success' => true, 'post' => $post];
    }
}
