<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostCommentLike;
use App\Models\PostCommentReaction;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'sharedPost.user', 'sharedPost.media', 'likes', 'comments.user', 'comments.likes', 'comments.reactions', 'comments.replies.user', 'comments.replies.reactions', 'media', 'reactions'])
            ->orderByDesc('created_at')->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        // Validate size client-side-ish; server hard limits set in .htaccess/php.ini
        $request->validate([
            'content' => 'nullable|string',
            'shared_post_id' => 'nullable|exists:posts,id',
            'files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,webm,mov,avi,mkv|max:153600', // 150MB
        ]);
        // Determine root_post_id according to share logic
        $rootId = null;
        if ($request->shared_post_id) {
            $shared = Post::select('id', 'root_post_id')->find($request->shared_post_id);
            if ($shared) {
                $rootId = $shared->root_post_id ?: $shared->id;
            }
        }
        $visibility = $request->input('visibility', 'public');
        $include = $request->input('visibility_include_ids');
        $exclude = $request->input('visibility_exclude_ids');
        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'shared_post_id' => $request->shared_post_id,
            'root_post_id' => $rootId,
            'visibility' => $visibility,
            'visibility_include_ids' => $include,
            'visibility_exclude_ids' => $exclude,
        ]);
        // Save media files if provided
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('posts', 'public');
                $type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
                $post->media()->create(['type' => $type, 'path' => $path]);
            }
        }
        if ($request->shared_post_id) {
            // increment on root post (original author)
            if ($rootId) {
                Post::where('id', $rootId)->increment('shares_count');
            }
        }

        return redirect()->route('posts.index');
    }

    public function like(Post $post)
    {
        $like = PostLike::firstOrCreate(['post_id' => $post->id, 'user_id' => Auth::id()]);
        $post->increment('likes_count');

        return back();
    }

    public function unlike(Post $post)
    {
        $deleted = PostLike::where(['post_id' => $post->id, 'user_id' => Auth::id()])->delete();
        if ($deleted) {
            $post->decrement('likes_count');
        }

        return back();
    }

    public function toggleLike(Post $post)
    {
        $userId = Auth::id();
        $existing = PostLike::where(['post_id' => $post->id, 'user_id' => $userId])->first();
        if ($existing) {
            $existing->delete();
            if ($post->likes_count > 0) {
                $post->decrement('likes_count');
            }
        } else {
            PostLike::create(['post_id' => $post->id, 'user_id' => $userId]);
            $post->increment('likes_count');
        }

        return back();
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate(['content' => 'required|string', 'parent_id' => 'nullable|exists:post_comments,id']);
        $comment = PostComment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);
        $post->increment('comments_count');

        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            $user = Auth::user();
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user_name' => $user->name,
                    'user_avatar' => get_avatar_url($user->avatar),
                    'post_id' => $post->id,
                    'parent_id' => $comment->parent_id,
                    'created_at' => $comment->created_at->diffForHumans(),
                ]
            ]);
        }

        return back();
    }

    public function toggleCommentLike(PostComment $comment)
    {
        $userId = Auth::id();
        $existing = PostCommentLike::where(['comment_id' => $comment->id, 'user_id' => $userId])->first();
        if ($existing) {
            $existing->delete();
            if ($comment->likes_count > 0) {
                $comment->decrement('likes_count');
            }
        } else {
            PostCommentLike::create(['comment_id' => $comment->id, 'user_id' => $userId]);
            $comment->increment('likes_count');
        }

        return back();
    }

    public function reactComment(Request $request, PostComment $comment)
    {
        $request->validate(['type' => 'required|in:like,love,haha,wow,sad,angry,none']);
        $userId = Auth::id();
        $existing = PostCommentReaction::where(['comment_id' => $comment->id, 'user_id' => $userId])->first();
        if ($request->type === 'none') {
            if ($existing) {
                $existing->delete();
                if ($comment->likes_count > 0) {
                    $comment->decrement('likes_count');
                }
            }

            return back();
        }
        if ($existing) {
            if ($existing->type !== $request->type) {
                $existing->update(['type' => $request->type]);
            }
        } else {
            PostCommentReaction::create(['comment_id' => $comment->id, 'user_id' => $userId, 'type' => $request->type]);
            $comment->increment('likes_count');
        }

        return back();
    }

    public function updateComment(Request $request, PostComment $comment)
    {
        $user = Auth::user();
        $isAdmin = $user && in_array($user->user_role, ['admin', 'super_admin'], true);
        if (! $user || ($comment->user_id !== $user->id && ! $isAdmin)) {
            abort(403);
        }
        $request->validate(['content' => 'required|string|max:2000']);
        $comment->update(['content' => $request->content]);

        return back()->with('success', 'Đã cập nhật bình luận');
    }

    public function deleteComment(PostComment $comment)
    {
        $user = Auth::user();
        $isAdmin = $user && in_array($user->user_role, ['admin', 'super_admin'], true);
        if (! $user || ($comment->user_id !== $user->id && ! $isAdmin)) {
            abort(403);
        }
        $comment->delete();

        return back()->with('success', 'Đã xóa bình luận');
    }

    public function listReactions(Post $post)
    {
        $tab = request('tab'); // all or specific type
        $query = $post->reactions()->with('user');
        if ($tab && in_array($tab, ['like', 'love', 'haha', 'wow', 'sad', 'angry'])) {
            $query->where('type', $tab);
        }
        $reactions = $query->orderByDesc('created_at')->paginate(20);
        $counts = $post->reactions()
            ->select('type', \Illuminate\Support\Facades\DB::raw('count(*) as c'))
            ->groupBy('type')->pluck('c', 'type');

        return response()->json([
            'counts' => $counts,
            'data' => $reactions->map(function ($r) {
                return [
                    'id' => $r->id,
                    'type' => $r->type,
                    'user' => [
                        'id' => $r->user->id,
                        'name' => $r->user->name,
                        'avatar' => $r->user->avatar ? asset('uploads/' . $r->user->avatar) : asset('images/default-avatar.png'),
                    ],
                ];
            }),
        ]);
    }

    public function counters(Post $post)
    {
        $counts = [
            'likes_count' => (int) $post->likes_count,
            'comments_count' => (int) $post->comments_count,
            'shares_count' => (int) ($post->shares_count ?? 0),
        ];
        $topTypes = $post->reactions()->select('type', \Illuminate\Support\Facades\DB::raw('count(*) as c'))
            ->groupBy('type')->orderByDesc('c')->limit(2)->pluck('type');

        return response()->json([
            'counts' => $counts,
            'top_types' => $topTypes,
        ]);
    }

    public function listShares(Post $post)
    {
        // Shares are represented by Posts with shared_post_id referencing the original post
        $shares = Post::with('user')
            ->where(function ($q) use ($post) {
                $q->where('root_post_id', $post->id)
                    ->orWhere('shared_post_id', $post->id); // backward compatibility
            })
            ->orderByDesc('created_at')
            ->get();
        $data = $shares->map(function ($p) {
            return [
                'id' => $p->id,
                'created_at' => $p->created_at ? $p->created_at->diffForHumans() : null,
                'user' => [
                    'id' => optional($p->user)->id,
                    'name' => optional($p->user)->name,
                    'avatar' => optional($p->user) && $p->user->avatar ? asset('uploads/' . $p->user->avatar) : asset('images/default-avatar.png'),
                ],
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function react(Request $request, Post $post)
    {
        $request->validate(['type' => 'required|in:like,love,haha,wow,sad,angry,none']);
        $userId = Auth::id();
        $existing = \App\Models\PostReaction::where(['post_id' => $post->id, 'user_id' => $userId])->first();
        if ($request->type === 'none') {
            if ($existing) {
                $existing->delete();
                if ($post->likes_count > 0) {
                    $post->decrement('likes_count');
                }
            }
        } else {
            if ($existing) {
                // Change type without touching total count
                if ($existing->type !== $request->type) {
                    $existing->update(['type' => $request->type]);
                }
            } else {
                \App\Models\PostReaction::create(['post_id' => $post->id, 'user_id' => $userId, 'type' => $request->type]);
                $post->increment('likes_count');
            }
        }

        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            $post->refresh();
            $topTypes = $post->reactions()
                ->select('type', \Illuminate\Support\Facades\DB::raw('count(*) as c'))
                ->groupBy('type')
                ->orderByDesc('c')
                ->limit(2)
                ->pluck('type')
                ->toArray();

            return response()->json([
                'success' => true,
                'topTypes' => $topTypes,
                'totalCount' => (int) $post->likes_count,
            ]);
        }

        return back();
    }

    public function destroy(Post $post)
    {
        $user = Auth::user();
        $isAdmin = $user && in_array($user->user_role, ['admin', 'super_admin'], true);
        if (! $user || ($post->user_id !== $user->id && ! $isAdmin)) {
            abort(403);
        }
        \Illuminate\Support\Facades\DB::transaction(function () use ($post) {
            // Delete media files from storage
            foreach ($post->media as $media) {
                if ($media->path) {
                    try {
                        Storage::disk('public')->delete($media->path);
                    } catch (\Throwable $e) {
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

        return redirect()->back()->with('success', 'Đã xóa bài viết');
    }

    public function update(Request $request, Post $post)
    {
        $user = Auth::user();
        $isAdmin = $user && in_array($user->user_role, ['admin', 'super_admin'], true);
        if (! $user || ($post->user_id !== $user->id && ! $isAdmin)) {
            abort(403);
        }
        $request->validate([
            'content' => 'nullable|string|max:5000',
            'files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,webm,mov,avi,mkv|max:153600', // 150MB
            'delete_media' => 'nullable|array',
            'delete_media.*' => 'integer',
        ]);

        $post->update(['content' => $request->content]);

        // Delete selected media
        if ($request->has('delete_media')) {
            $mediaToDelete = $post->media()->whereIn('id', $request->delete_media)->get();
            foreach ($mediaToDelete as $media) {
                \Storage::disk('public')->delete($media->path);
                $media->delete();
            }
        }

        // Add new media files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('posts', 'public');
                $type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
                $post->media()->create(['type' => $type, 'path' => $path]);
            }
        }

        return redirect()->back()->with('success', 'Đã cập nhật bài viết');
    }
}
