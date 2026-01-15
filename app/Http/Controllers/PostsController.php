<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * PostsController - HTTP layer for posts functionality
 * Business logic delegated to PostService
 * Refactored for proper MVC architecture
 */
class PostsController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        $posts = Post::with([
            'user', 'sharedPost.user', 'sharedPost.media', 'likes',
            'comments.user', 'comments.likes', 'comments.reactions',
            'comments.replies.user', 'comments.replies.reactions',
            'media', 'reactions'
        ])->orderByDesc('created_at')->paginate(10);

        // Get ongoing tournaments (top 3)
        $ongoingTournaments = \App\Models\Tournament::with(['game', 'schedule'])
            ->where('status', 'ongoing')
            ->orWhere(function($query) {
                $query->where('status', 'registration')
                    ->whereHas('schedule', function($q) {
                        $q->where('start_date', '>=', now())
                            ->where('registration_deadline', '>=', now());
                    });
            })
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Get top honor users (top 3)
        $topHonorUsers = \App\Models\HonorVote::select('voted_user_id', DB::raw('SUM(weight) as total_weight'), DB::raw('COUNT(*) as vote_count'))
            ->whereNotNull('voted_user_id')
            ->groupBy('voted_user_id')
            ->orderByDesc('total_weight')
            ->limit(3)
            ->with('votedUser.profile')
            ->get();

        return view('posts.index', compact('posts', 'ongoingTournaments', 'topHonorUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string',
            'shared_post_id' => 'nullable|exists:posts,id',
            'files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,webm,mov,avi,mkv|max:153600',
        ]);

        $files = $request->hasFile('files') ? $request->file('files') : null;

        $this->postService->createPost(
            Auth::user(),
            $request->only(['content', 'shared_post_id', 'visibility', 'visibility_include_ids', 'visibility_exclude_ids']),
            $files
        );

        return redirect()->route('posts.index');
    }

    public function like(Post $post)
    {
        $post->like(Auth::id());
        $post->increment('likes_count');
        return back();
    }

    public function unlike(Post $post)
    {
        $post->unlike(Auth::id());
        return back();
    }

    public function toggleLike(Post $post)
    {
        $this->postService->toggleLike($post, Auth::id());
        return back();
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:post_comments,id'
        ]);

        $comment = $this->postService->addComment(
            $post,
            Auth::user(),
            $request->content,
            $request->parent_id
        );

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

    public function getComments(Request $request, Post $post)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);

        $comments = PostComment::with(['user', 'replies.user', 'reactions'])
            ->where('post_id', $post->id)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'asc')
            ->skip($offset)
            ->take($limit)
            ->get();

        $data = $comments->map(function ($c) {
            $myReaction = auth()->check()
                ? optional($c->reactions->firstWhere('user_id', auth()->id()))->type
                : null;

            return [
                'id' => $c->id,
                'content' => $c->content,
                'user_name' => $c->user->name ?? 'User',
                'user_avatar' => get_avatar_url($c->user?->avatar),
                'user_id' => $c->user_id,
                'created_at' => $c->created_at->diffForHumans(),
                'likes_count' => $c->likes_count ?? 0,
                'my_reaction' => $myReaction,
                'replies' => $c->replies->map(fn($reply) => [
                    'id' => $reply->id,
                    'content' => $reply->content,
                    'user_name' => $reply->user->name ?? 'User',
                    'user_avatar' => get_avatar_url($reply->user?->avatar),
                    'user_id' => $reply->user_id,
                    'parent_user_name' => $c->user->name ?? 'User',
                    'created_at' => $reply->created_at->diffForHumans(),
                    'likes_count' => $reply->likes_count ?? 0,
                    'my_reaction' => auth()->check()
                        ? optional($reply->reactions->firstWhere('user_id', auth()->id()))->type
                        : null,
                ]),
            ];
        });

        return response()->json(['comments' => $data]);
    }

    public function toggleCommentLike(PostComment $comment)
    {
        $userId = Auth::id();
        $existing = \App\Models\PostCommentLike::where(['comment_id' => $comment->id, 'user_id' => $userId])->first();

        if ($existing) {
            $existing->delete();
            if ($comment->likes_count > 0) $comment->decrement('likes_count');
        } else {
            \App\Models\PostCommentLike::create(['comment_id' => $comment->id, 'user_id' => $userId]);
            $comment->increment('likes_count');
        }

        return back();
    }

    public function reactComment(Request $request, PostComment $comment)
    {
        $request->validate(['type' => 'required|in:like,love,haha,wow,sad,angry,none']);
        $this->postService->reactToComment($comment, Auth::id(), $request->type);
        return back();
    }

    public function updateComment(Request $request, PostComment $comment)
    {
        $user = Auth::user();
        $isAdmin = $user && in_array($user->user_role, ['admin', 'super_admin'], true);

        if (!$user || ($comment->user_id !== $user->id && !$isAdmin)) {
            abort(403);
        }

        $request->validate(['content' => 'required|string|max:2000']);
        $comment->update(['content' => $request->content]);

        return back()->with('success', 'Đã cập nhật bình luận');
    }

    public function deleteComment(PostComment $comment)
    {
        $result = $this->postService->deleteComment($comment, Auth::user());

        if (!$result['success']) {
            abort(403);
        }

        return back()->with('success', 'Đã xóa bình luận');
    }

    public function listReactions(Post $post)
    {
        $tab = request('tab');
        $query = $post->reactions()->with('user');

        if ($tab && in_array($tab, ['like', 'love', 'haha', 'wow', 'sad', 'angry'])) {
            $query->where('type', $tab);
        }

        $reactions = $query->orderByDesc('created_at')->paginate(20);
        $counts = $post->reactions()
            ->select('type', DB::raw('count(*) as c'))
            ->groupBy('type')
            ->pluck('c', 'type');

        return response()->json([
            'counts' => $counts,
            'data' => $reactions->map(fn($r) => [
                'id' => $r->id,
                'type' => $r->type,
                'user' => [
                    'id' => $r->user->id,
                    'name' => $r->user->name,
                    'avatar' => $r->user->getDisplayAvatar(),
                ],
            ]),
        ]);
    }

    public function counters(Post $post)
    {
        $counts = [
            'likes_count' => (int) $post->likes_count,
            'comments_count' => (int) $post->comments_count,
            'shares_count' => (int) ($post->shares_count ?? 0),
        ];

        $topTypes = $post->reactions()
            ->select('type', DB::raw('count(*) as c'))
            ->groupBy('type')
            ->orderByDesc('c')
            ->limit(2)
            ->pluck('type');

        return response()->json(['counts' => $counts, 'top_types' => $topTypes]);
    }

    public function listShares(Post $post)
    {
        $shares = Post::with('user')
            ->where(fn($q) => $q->where('root_post_id', $post->id)->orWhere('shared_post_id', $post->id))
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'data' => $shares->map(fn($p) => [
                'id' => $p->id,
                'created_at' => $p->created_at?->diffForHumans(),
                'user' => [
                    'id' => optional($p->user)->id,
                    'name' => optional($p->user)->name,
                    'avatar' => get_avatar_url(optional($p->user)->avatar),
                ],
            ]),
        ]);
    }

    public function react(Request $request, Post $post)
    {
        $request->validate(['type' => 'required|in:like,love,haha,wow,sad,angry,none']);

        $result = $this->postService->react($post, Auth::id(), $request->type);

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        return back();
    }

    public function destroy(Post $post)
    {
        $result = $this->postService->deletePost($post, Auth::user());

        if (!$result['success']) {
            abort(403);
        }

        return redirect()->back()->with('success', 'Đã xóa bài viết');
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'nullable|string|max:5000',
            'files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,webm,mov,avi,mkv|max:153600',
            'delete_media' => 'nullable|array',
            'delete_media.*' => 'integer',
        ]);

        $newFiles = $request->hasFile('files') ? $request->file('files') : null;

        $result = $this->postService->updatePost(
            $post,
            Auth::user(),
            $request->only(['content']),
            $newFiles,
            $request->delete_media
        );

        if (!$result['success']) {
            abort(403);
        }

        return redirect()->back()->with('success', 'Đã cập nhật bài viết');
    }
}
