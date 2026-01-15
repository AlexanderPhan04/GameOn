<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostManagementController extends Controller
{
    /**
     * Hiển thị danh sách bài viết
     */
    public function index(Request $request)
    {
        $query = Post::with(['user', 'media'])
            ->withCount(['comments', 'reactions', 'likes']);

        // Tìm kiếm
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Lọc theo user
        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        // Lọc theo ngày
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Sắp xếp
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $posts = $query->paginate(20)->withQueryString();

        // Thống kê
        $stats = [
            'total_posts' => Post::count(),
            'total_comments' => PostComment::count(),
            'posts_today' => Post::whereDate('created_at', today())->count(),
            'comments_today' => PostComment::whereDate('created_at', today())->count(),
        ];

        return view('admin.posts.index', compact('posts', 'stats'));
    }

    /**
     * Xem chi tiết bài viết
     */
    public function show(Post $post)
    {
        $post->load([
            'user',
            'media',
            'comments' => function ($q) {
                $q->with(['user', 'replies.user'])->whereNull('parent_id')->latest();
            },
            'reactions.user',
            'sharedPost.user',
        ]);

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Xóa bài viết
     */
    public function destroy(Post $post)
    {
        DB::transaction(function () use ($post) {
            // Xóa media files
            foreach ($post->media as $media) {
                if ($media->path && \Storage::disk('public')->exists($media->path)) {
                    \Storage::disk('public')->delete($media->path);
                }
            }

            // Xóa các quan hệ
            $post->media()->delete();
            $post->comments()->delete();
            $post->reactions()->delete();
            $post->likes()->delete();
            $post->mentions()->delete();
            $post->visibilityUsers()->delete();

            // Xóa bài viết
            $post->delete();
        });

        return redirect()->route('admin.posts.index')
            ->with('success', 'Đã xóa bài viết thành công');
    }

    /**
     * Xóa nhiều bài viết
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'post_ids' => 'required|array',
            'post_ids.*' => 'exists:posts,id',
        ]);

        $count = 0;
        foreach ($request->post_ids as $postId) {
            $post = Post::find($postId);
            if ($post) {
                DB::transaction(function () use ($post) {
                    foreach ($post->media as $media) {
                        if ($media->path && \Storage::disk('public')->exists($media->path)) {
                            \Storage::disk('public')->delete($media->path);
                        }
                    }
                    $post->media()->delete();
                    $post->comments()->delete();
                    $post->reactions()->delete();
                    $post->likes()->delete();
                    $post->mentions()->delete();
                    $post->visibilityUsers()->delete();
                    $post->delete();
                });
                $count++;
            }
        }

        return redirect()->route('admin.posts.index')
            ->with('success', "Đã xóa {$count} bài viết thành công");
    }

    /**
     * Danh sách bình luận
     */
    public function comments(Request $request)
    {
        $query = PostComment::with(['user', 'post.user']);

        // Tìm kiếm
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Lọc theo bài viết
        if ($postId = $request->input('post_id')) {
            $query->where('post_id', $postId);
        }

        // Lọc theo user
        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        // Sắp xếp
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $comments = $query->paginate(30)->withQueryString();

        return view('admin.posts.comments', compact('comments'));
    }

    /**
     * Xóa bình luận
     */
    public function destroyComment(PostComment $comment)
    {
        $postId = $comment->post_id;

        DB::transaction(function () use ($comment) {
            // Xóa replies trước
            $comment->replies()->delete();
            // Xóa reactions
            $comment->reactions()->delete();
            $comment->likes()->delete();
            // Xóa comment
            $comment->delete();
        });

        // Cập nhật số lượng comment của post
        Post::where('id', $postId)->decrement('comments_count');

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Đã xóa bình luận']);
        }

        return back()->with('success', 'Đã xóa bình luận thành công');
    }

    /**
     * Xóa nhiều bình luận
     */
    public function bulkDeleteComments(Request $request)
    {
        $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:post_comments,id',
        ]);

        $count = 0;
        $postIds = [];

        foreach ($request->comment_ids as $commentId) {
            $comment = PostComment::find($commentId);
            if ($comment) {
                $postIds[] = $comment->post_id;
                DB::transaction(function () use ($comment) {
                    $comment->replies()->delete();
                    $comment->reactions()->delete();
                    $comment->likes()->delete();
                    $comment->delete();
                });
                $count++;
            }
        }

        // Cập nhật số lượng comment
        foreach (array_unique($postIds) as $postId) {
            $actualCount = PostComment::where('post_id', $postId)->count();
            Post::where('id', $postId)->update(['comments_count' => $actualCount]);
        }

        return redirect()->route('admin.posts.comments')
            ->with('success', "Đã xóa {$count} bình luận thành công");
    }
}
