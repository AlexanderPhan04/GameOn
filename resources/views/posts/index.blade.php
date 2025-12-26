@extends('layouts.app')

@section('title','Bài viết')

@section('content')
<div class="container py-4">
    <style>
        .composer-card{backdrop-filter:blur(12px);background:rgba(255,255,255,.85);border:1px solid rgba(148,163,184,.25);border-radius:18px;box-shadow:0 18px 60px rgba(2,6,23,.12)}
        .feed-wrap{max-width:720px;margin:0 auto}
        .composer-trigger{border-radius:20px;background:#f1f5f9;border:1px solid #e2e8f0;padding:.75rem 1rem;color:#475569}
        .composer-trigger:hover{background:#e2e8f0}
        .modern-modal .modal-content{border-radius:20px;border:1px solid rgba(148,163,184,.25);box-shadow:0 30px 80px rgba(2,6,23,.25)}
        .modern-toolbar .btn{border-radius:12px;border:1px dashed #e2e8f0}
        .modern-toolbar .btn i{font-size:1.2rem}
        .post-card{border-radius:12px;border:1px solid #e2e8f0}
        .post-header{display:flex;justify-content:space-between;align-items:center}
        .post-header .left{display:flex;align-items:center;gap:.75rem}
        .avatar{width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2)}
        .avatar-link{display:inline-block;cursor:pointer}
        .post-name{font-weight:600}
        .post-time{color:#64748b;font-size:.85rem}
        .media-grid img,.media-grid video{border-radius:8px}
        /* Post media auto-fit by orientation */
        .post-media{display:block;max-width:100%;height:auto;border-radius:8px;margin:0 auto}
        .post-media-video{width:100%;height:auto;border-radius:8px}
        /* Gallery grid */
        .post-gallery{display:grid;gap:6px}
        .post-gallery.cols-2{grid-template-columns:repeat(2,1fr)}
        .post-gallery.cols-3{grid-template-columns:repeat(3,1fr)}
        .post-gallery.cols-4{grid-template-columns:repeat(4,1fr)}
        .post-gallery.cols-5{grid-template-columns:repeat(5,1fr)}
        .gallery-tile{position:relative;border-radius:8px;overflow:hidden;aspect-ratio:1/1}
        .gallery-tile img{width:100%;height:100%;object-fit:cover;display:block}
        .gallery-tile.more:before{content:"";position:absolute;inset:0;background:rgba(0,0,0,.35)}
        .gallery-tile.more span{position:absolute;inset:auto 0 0 0;margin:auto;color:#fff;font-weight:700;text-align:center;font-size:1.25rem}
        /* Vertical collage layout */
        .post-collage-split{display:grid;grid-template-columns:2fr 1fr;gap:6px;height:360px}
        .collage-left,.collage-right{border-radius:8px;overflow:hidden}
        .collage-left img{width:100%;height:100%;object-fit:cover;display:block}
        .collage-right{display:grid;gap:6px}
        .collage-right.cols-1{grid-template-rows:repeat(1,1fr)}
        .collage-right.cols-2{grid-template-rows:repeat(2,1fr)}
        .collage-right.cols-3{grid-template-rows:repeat(3,1fr)}
        .collage-right.cols-4{grid-template-rows:repeat(4,1fr)}
        .collage-right .tile{position:relative;border-radius:8px;overflow:hidden}
        .collage-right .tile img{width:100%;height:100%;object-fit:cover;display:block}
        .collage-right .tile.more:before{content:"";position:absolute;inset:0;background:rgba(0,0,0,.35)}
        .collage-right .tile.more span{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1.5rem}
        .stats-line{display:flex;justify-content:space-between;align-items:center;color:#64748b;font-size:.9rem}
        .counts-bar{display:flex;align-items:center;color:#64748b;font-size:.9rem;padding:.25rem 0;border-bottom:1px solid #e2e8f0;margin-top:.25rem}
        .counts-right{margin-left:auto}
        .stats-line .left{display:flex;align-items:center;gap:.25rem}
        .icon-like{display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;background:#3b82f6;color:#fff;border-radius:50%;font-size:.7rem}
        .counts-icons{display:inline-flex;align-items:center}
        .counts-icons .icon-like{margin-right:0;box-shadow:0 0 0 2px #fff}
        .counts-icons .icon-like + .icon-like{margin-left:-6px}
        .icon-like-like{background:#3b82f6}
        .icon-like-love{background:#ef4444}
        .icon-like-haha{background:#f59e0b}
        .icon-like-wow{background:#06b6d4}
        .icon-like-sad{background:#64748b}
        .icon-like-angry{background:#f97316}
        .post-actions{display:flex;gap:.5rem}
        .post-actions > *{flex:1}
        .action-col{display:flex;flex-direction:column;gap:4px;align-items:center}
        .btn-action{display:flex;align-items:center;justify-content:center;gap:.5rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:.5rem 0;color:#334155;width:100%}
        .btn-action:hover{background:#eef2ff}
        .btn-action.active{background:#eef2ff;border-color:#c7d2fe;color:#1e3a8a;font-weight:600}
        /* Active reaction color accents */
        .btn-action.reacted-like{color:#2563eb}
        .btn-action.reacted-love{color:#ef4444}
        .btn-action.reacted-haha{color:#f59e0b}
        .btn-action.reacted-wow{color:#06b6d4}
        .btn-action.reacted-sad{color:#64748b}
        .btn-action.reacted-angry{color:#f97316}
        .reaction-menu .btn{border:none;font-size:1.2rem}
        /* Reactions popover */
        .reactions-popover{position:absolute;bottom:115%;left:0;right:0;display:none;justify-content:center;gap:.35rem;background:#fff;padding:.4rem .5rem;border:1px solid #e2e8f0;border-radius:999px;box-shadow:0 12px 30px rgba(2,6,23,.15);z-index:5}
        .reactions-popover .reaction{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:transform .12s ease}
        .reactions-popover .reaction:hover{transform:translateY(-3px) scale(1.05)}
        .reaction-like{background:#3b82f6;color:#fff}
        .reaction-love{background:#ef4444;color:#fff}
        .reaction-haha{background:#f59e0b;color:#fff}
        .reaction-wow{background:#06b6d4;color:#fff}
        .reaction-sad{background:#64748b;color:#fff}
        .reaction-angry{background:#f97316;color:#fff}
        .like-wrapper{position:relative}
        .likes-count-link{color:inherit;text-decoration:none;border-bottom:1px solid transparent}
        .likes-count-link:hover{text-decoration:underline}
        /* Ensure modals appear above fixed header */
        .modal{z-index:100010}
        .modal-backdrop{z-index:100005}
        /* Reactions modal size */
        .reactions-modal .modal-dialog{max-width:720px}
        /* Comment bubble */
        .comment-item{display:flex;align-items:flex-start;gap:.5rem}
        .comment-bubble{background:#f1f5f9;border:1px solid #e2e8f0;border-radius:16px;padding:.5rem .75rem;max-width:100%}
        .comment-author{font-weight:600;font-size:.92rem;color:#0f172a;margin-bottom:2px}
        .comment-text{color:#1f2937;white-space:pre-wrap;word-break:break-word}
        .comment-avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);flex-shrink:0}
        .comment-actions{display:flex;gap:12px;align-items:center;color:#64748b;font-size:.85rem;margin-top:4px}
        .comment-action{cursor:pointer}
        .comment-action:hover{color:#111827}
        .c-react-pop{position:absolute;bottom:115%;left:0;display:none;gap:.35rem;background:#fff;padding:.35rem .45rem;border:1px solid #e2e8f0;border-radius:999px;box-shadow:0 10px 24px rgba(2,6,23,.15);z-index:5}
        .c-react-pop .r{width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff}
        .r.like{background:#3b82f6}
        .r.love{background:#ef4444}
        .r.haha{background:#f59e0b}
        .r.wow{background:#06b6d4}
        .r.sad{background:#64748b}
        .r.angry{background:#f97316}
        .c-like-icon{display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;border-radius:50%;color:#fff;margin-right:6px;font-size:.65rem}
        .c-like-like{background:#3b82f6}
        .c-like-love{background:#ef4444}
        .c-like-haha{background:#f59e0b}
        .c-like-wow{background:#06b6d4}
        .c-like-sad{background:#64748b}
        .c-like-angry{background:#f97316}
        .comment-reaction-summary{display:inline-flex;align-items:center;gap:6px;margin-left:6px}
        .comment-time{color:#94a3b8;font-size:.82rem;margin-right:4px}
        .link-underline{cursor:pointer}
        .link-underline:hover{text-decoration:underline}
        @media (max-width:768px){.composer-trigger{padding:.6rem .9rem}}
    </style>
    <h3 class="mb-3 text-center">{{ __('app.nav.posts') }}</h3>

    <!-- Composer card (open modal) -->
    <div class="feed-wrap">
    <div class="card mb-3 composer-card">
        <div class="card-body d-flex gap-3 align-items-center">
            @auth
            @php $me = auth()->user(); $meAvatar = get_avatar_url($me?->avatar); @endphp
            <a href="{{ route('profile.show') }}" class="avatar-link" title="{{ __('app.nav.profile') }}">
                <div class="rounded-circle" style="width:42px;height:42px;background:#ddd url('{{ $meAvatar }}') center/cover no-repeat"></div>
            </a>
            @else
            <div class="rounded-circle" style="width:42px;height:42px;background:linear-gradient(135deg,#667eea,#764ba2)"></div>
            @endauth
            <button class="form-control text-start composer-trigger" data-bs-toggle="modal" data-bs-target="#postComposerModal">{{ __('app.feed.whats_on_your_mind') }}</button>
        </div>
        </div>
    </div>

    <!-- Composer Modal -->
    <div class="modal fade" id="postComposerModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered modern-modal">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ __('app.feed.create_post') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" id="postComposerForm">
            @csrf
            <div class="modal-body">
              <textarea class="form-control border-0" name="content" rows="4" placeholder="{{ __('app.feed.whats_on_your_mind') }}" style="font-size:1.15rem"></textarea>
              <div id="mediaPreview" class="row g-2 mt-2 media-grid"></div>

              <div class="d-flex justify-content-between align-items-center modern-toolbar border rounded p-2 mt-3">
                <div class="d-flex align-items-center gap-2">
                  <div class="dropdown">
                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="visibilityBtn">Công khai</button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="#" data-vis="public">Công khai</a></li>
                      <li><a class="dropdown-item" href="#" data-vis="friends">Bạn bè</a></li>
                      <li><a class="dropdown-item" href="#" data-vis="friends_except">Bạn bè ngoại trừ…</a></li>
                      <li><a class="dropdown-item" href="#" data-vis="specific_friends">Bạn bè cụ thể…</a></li>
                      <li><a class="dropdown-item" href="#" data-vis="only_me">Chỉ mình tôi</a></li>
                      <li><a class="dropdown-item" href="#" data-vis="custom">Tùy chỉnh…</a></li>
                    </ul>
                  </div>
                  <span class="text-muted">Thêm vào bài viết của bạn</span>
                </div>
                <div class="d-flex gap-2">
                  <label class="btn btn-sm btn-light" title="Ảnh/Video">
                    <i class="fas fa-photo-video text-success"></i>
                    <input type="file" name="files[]" multiple class="d-none" id="composerFiles">
                  </label>
                  <button type="button" class="btn btn-sm btn-light" id="tagBtn" title="Gắn thẻ bạn bè"><i class="fas fa-user-tag text-primary"></i></button>
                  <button type="button" class="btn btn-sm btn-light" id="feelingBtn" title="Cảm xúc/Hoạt động"><i class="fas fa-smile text-warning"></i></button>
                </div>
              </div>

              <div id="tagContainer" class="mt-2" style="display:none;">
                <label class="form-label small">Gắn thẻ</label>
                <input type="text" id="tagInput" class="form-control" placeholder="Nhập tên để gắn thẻ...">
                <div id="tagResults" class="list-group"></div>
                <div class="mt-1 small" id="taggedList"></div>
              </div>

              <input type="hidden" name="mentions" id="mentionsField">
              <input type="hidden" name="visibility" id="visibilityField" value="public">
              <input type="hidden" name="visibility_include_ids" id="visibilityIncludeField">
              <input type="hidden" name="visibility_exclude_ids" id="visibilityExcludeField">
            </div>
            <div class="modal-footer">
              <button class="btn btn-primary w-100">Đăng</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    @if($posts->isEmpty())
    <div class="feed-wrap">
      <div class="card composer-card p-4 mb-4 text-center">
        <div class="mb-2" style="font-size:2.25rem">📰</div>
        <h5 class="fw-semibold mb-2">Bảng tin của bạn còn trống</h5>
        <p class="text-muted mb-3">Hãy kết bạn để xem thêm bài viết mới từ mọi người.</p>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
          <a href="{{ route('search.view') }}" class="btn btn-primary"><i class="fas fa-user-plus me-1"></i> Tìm bạn bè</a>
          <a href="{{ route('players.index') }}" class="btn btn-outline-secondary"><i class="fas fa-users me-1"></i> Khám phá cộng đồng</a>
        </div>
      </div>
      <div style="height:40vh"></div>
    </div>
    @endif

    @foreach($posts as $post)
    <div class="feed-wrap">
    <div class="card mb-3 post-card" data-post-card="{{ $post->id }}">
        <div class="card-body">
            <div class="post-header">
                <div class="left">
                    <a href="{{ (auth()->id() === $post->user_id) ? route('profile.show') : route('profile.show-user', $post->user_id) }}" class="avatar-link" title="Xem trang cá nhân">
                        @php $avatarUrl = get_avatar_url($post->user?->avatar); @endphp
                        <div class="avatar" style="background:#ddd url('{{ $avatarUrl }}') center/cover no-repeat"></div>
                    </a>
                    <div>
                        <div class="post-name"><a href="{{ (auth()->id() === $post->user_id) ? route('profile.show') : route('profile.show-user', $post->user_id) }}" class="text-decoration-none">{{ $post->user->name ?? 'User' }}</a></div>
                        <div class="post-time link-underline" data-open-post-modal="{{ $post->id }}" data-timestamp="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @auth
                @php $canDelete = (auth()->id() === $post->user_id) || (in_array(auth()->user()->user_role,['admin','super_admin'])); @endphp
                <div class="d-flex gap-2">
                @if($canDelete)
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editPostModal{{ $post->id }}">
                                    <i class="fas fa-pen me-2"></i>Chỉnh sửa bài viết
                                </button>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('posts.destroy', $post) }}" class="m-0 delete-post-form" data-post-id="{{ $post->id }}">
                    @csrf
                    @method('DELETE')
                                    <button class="dropdown-item text-danger" type="submit"><i class="fas fa-trash me-2"></i>Xóa</button>
                </form>
                            </li>
                        </ul>
                    </div>
                @endif
                </div>
                @endauth
            </div>
            @if($post->content)
            <p class="mt-2">{{ $post->content }}</p>
            @endif

            <!-- Edit Modal -->
            <div class="modal fade" id="editPostModal{{ $post->id }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa bài viết</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <form method="POST" action="{{ route('posts.update',$post) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                      <textarea name="content" class="form-control" rows="5" placeholder="Nội dung...">{{ old('content',$post->content) }}</textarea>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-primary">Lưu</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            @if($post->shared_post_id && $post->sharedPost)
            @php $sp = $post->sharedPost; $spAvatar = get_avatar_url($sp->user?->avatar); @endphp
            <div class="border rounded p-2 bg-light mt-2">
                @if($sp->media && $sp->media->count())
                    @php $spImages = $sp->media->where('type','image'); $spVideos = $sp->media->where('type','!=','image'); @endphp
                    @if($spImages->count())
                        @php $cnt=$spImages->count(); @endphp
                        @if($cnt==1)
                            <img src="{{ asset('storage/'.$spImages->first()->path) }}" class="post-media mt-2 mb-2">
                        @elseif($cnt==2)
                            <div class="mt-2 post-gallery cols-2">
                                @foreach($spImages as $im)
                                    <div class="gallery-tile"><img src="{{ asset('storage/'.$im->path) }}" alt=""></div>
                                @endforeach
                            </div>
                        @else
                            @php $rightVisible=min($cnt-1,4); $remain=max(0,($cnt-1)-$rightVisible); @endphp
                            <div class="mt-2 post-collage-split">
                                <div class="collage-left"><img src="{{ asset('storage/'.$spImages->first()->path) }}" alt=""></div>
                                <div class="collage-right cols-{{ $rightVisible }}">
                                    @foreach($spImages->slice(1)->take($rightVisible) as $i=>$im)
                                        @php $isMore = $remain>0 && $i===($rightVisible-1); @endphp
                                        <div class="tile {{ $isMore ? 'more':'' }}">
                                            <img src="{{ asset('storage/'.$im->path) }}" alt="">
                                            @if($isMore)<span>+{{ $remain }}</span>@endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                    @if($spVideos->count())
                        @foreach($spVideos as $v)
                            <video controls class="post-media-video mt-2 mb-2"><source src="{{ asset('storage/'.$v->path) }}"></video>
                        @endforeach
                    @endif
                @endif
                <div class="d-flex align-items-center gap-2 mt-2">
                    <img src="{{ $spAvatar }}" class="rounded-circle" style="width:28px;height:28px;object-fit:cover"/>
                    <div>
                        <div class="fw-semibold small">{{ $sp->user->name ?? 'User' }}</div>
                        <div class="text-muted small">{{ optional($sp->created_at)->diffForHumans() }}</div>
                    </div>
                </div>
                @if($sp->content)
                    <div class="mt-1">{{ $sp->content }}</div>
                @endif
            </div>
            @endif

            
            @if($post->media && $post->media->count())
                @php
                    $images = $post->media->where('type','image');
                    $videos = $post->media->where('type','!=','image');
                @endphp
                @if($images->count())
                    @php
                        $imgCount = $images->count();
                    @endphp
                    @if($imgCount==1)
                        <img src="{{ asset('storage/'.$images->first()->path) }}" class="post-media mt-2 mb-2">
                    @elseif($imgCount==2)
                        <div class="mt-2 post-gallery cols-2">
                            @foreach($images as $im)
                                <div class="gallery-tile"><img src="{{ asset('storage/'.$im->path) }}" alt=""></div>
                            @endforeach
                        </div>
                        @else
                        @php
                            $rightVisible = min($imgCount-1,4);
                            $remain = max(0, ($imgCount-1) - $rightVisible);
                        @endphp
                        <div class="mt-2 post-collage-split">
                            <div class="collage-left"><img src="{{ asset('storage/'.$images->first()->path) }}" alt=""></div>
                            <div class="collage-right cols-{{ $rightVisible }}">
                                @foreach($images->slice(1)->take($rightVisible) as $i=>$im)
                                    @php $isMore = $remain>0 && $i === ($rightVisible-1); @endphp
                                    <div class="tile {{ $isMore ? 'more':'' }}">
                                        <img src="{{ asset('storage/'.$im->path) }}" alt="">
                                        @if($isMore)
                                            <span>+{{ $remain }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
                @if($videos->count())
                    @foreach($videos as $v)
                        <video controls class="post-media-video mt-2 mb-2">
                            <source src="{{ asset('storage/'.$v->path) }}">
                            </video>
                    @endforeach
                @endif
            @endif

            <div class="mt-2 counts-bar">
                @php
                    $currentUserId = auth()->id();
                    $statIconClassMap = [
                        'like' => 'far fa-thumbs-up',
                        'love' => 'fas fa-heart',
                        'haha' => 'fas fa-laugh',
                        'wow' => 'fas fa-surprise',
                        'sad' => 'fas fa-sad-tear',
                        'angry' => 'fas fa-angry'
                    ];
                    // Top 2 reaction types by count
                    $typeCounts = optional($post->reactions)->groupBy('type')->map->count()->sortDesc();
                    $topTypes = $typeCounts ? $typeCounts->keys()->take(2) : collect();
                @endphp
                <div class="left {{ $post->likes_count>0 ? '' : 'd-none' }}" id="likes-wrap-{{ $post->id }}">
                    <span class="counts-icons">
                        @foreach($topTypes as $t)
                            <span class="icon-like icon-like-{{ $t }}">
                                <i class="{{ $statIconClassMap[$t] ?? 'far fa-thumbs-up' }}"></i>
                            </span>
                        @endforeach
                    </span>
                    @if($topTypes->isEmpty())
                        <span class="icon-like icon-like-like fallback-like"><i class="far fa-thumbs-up"></i></span>
                    @endif
                    <a href="#" class="likes-count-link" data-reactions-modal="{{ $post->id }}" id="likes-count-{{ $post->id }}">{{ $post->likes_count }}</a>
                </div>
                <div class="counts-right">
                    <span class="link-underline" data-open-post-modal="{{ $post->id }}">
                        <span class="comments-link" id="comments-count-{{ $post->id }}">{{ $post->comments_count }}</span> {{ __('app.feed.comments', [], 'en') ?? 'comments' }}
                    </span>
                    ·
                    <span class="link-underline" data-open-shares="{{ $post->id }}">
                        <span class="shares-link" id="shares-count-{{ $post->id }}">{{ $post->shares_count ?? 0 }}</span> {{ __('app.feed.shares', [], 'en') ?? 'shares' }}
                    </span>
                </div>
            </div>

            <div class="mt-2 post-actions">
                @php
                    $currentUserId = auth()->id();
                    $myReaction = $currentUserId ? $post->reactions->firstWhere('user_id', $currentUserId) : null;
                    $hasLiked = $currentUserId ? $post->likes->contains('user_id', $currentUserId) : false;
                    $reactionType = $myReaction->type ?? ($hasLiked ? 'like' : null);
                    $hasReacted = (bool) $reactionType;
                    $reactionTextMap = ['like'=>'Thích','love'=>'Yêu thích','haha'=>'Haha','wow'=>'Wow','sad'=>'Buồn','angry'=>'Phẫn nộ'];
                    $reactionIconMap = [
                        'like' => 'far fa-thumbs-up',
                        'love' => 'fas fa-heart',
                        'haha' => 'fas fa-laugh',
                        'wow' => 'fas fa-surprise',
                        'sad' => 'fas fa-sad-tear',
                        'angry' => 'fas fa-angry'
                    ];
                @endphp
                <div class="action-col w-100">
                    <div class="like-wrapper w-100">
                <button 
                            class="btn-action w-100 like-btn {{ $hasReacted ? 'active reacted-'.$reactionType : '' }}"
                    type="button"
                            data-like-endpoint="{{ route('posts.like',$post) }}"
                            data-react-endpoint="{{ route('posts.react',$post) }}"
                    data-post-id="{{ $post->id }}"
                            data-reaction="{{ $reactionType ?? '' }}">
                            <i class="{{ $reactionIconMap[$reactionType] ?? 'far fa-thumbs-up' }}"></i> {{ $reactionTextMap[$reactionType] ?? 'Thích' }}
                </button>
                        <div class="reactions-popover" data-for-post="{{ $post->id }}">
                            <div class="reaction reaction-like" data-type="like" title="Thích"><i class="far fa-thumbs-up"></i></div>
                            <div class="reaction reaction-love" data-type="love" title="Yêu thích"><i class="fas fa-heart"></i></div>
                            <div class="reaction reaction-haha" data-type="haha" title="Haha"><i class="fas fa-laugh"></i></div>
                            <div class="reaction reaction-wow" data-type="wow" title="Wow"><i class="fas fa-surprise"></i></div>
                            <div class="reaction reaction-sad" data-type="sad" title="Buồn"><i class="fas fa-sad-tear"></i></div>
                            <div class="reaction reaction-angry" data-type="angry" title="Phẫn nộ"><i class="fas fa-angry"></i></div>
                        </div>
                    </div>
                </div>
                <div class="action-col w-100">
                    <button class="btn-action w-100" data-bs-toggle="collapse" data-bs-target="#cmt{{ $post->id }}"><i class="far fa-comment"></i> {{ __('app.feed.comment') }}</button>
                </div>
                <div class="action-col w-100">
                <form method="POST" action="{{ route('posts.store') }}" class="d-inline-block w-100 m-0">
                    @csrf
                    <input type="hidden" name="shared_post_id" value="{{ $post->id }}">
                        <button class="btn-action w-100" type="submit"><i class="far fa-share-square"></i> {{ __('app.feed.share') }}</button>
                </form>
                </div>
            </div>

            <div id="cmt{{ $post->id }}" class="collapse mt-2">
                <form method="POST" action="{{ route('posts.comment',$post) }}" class="d-flex gap-2">@csrf
                    <input name="content" class="form-control" placeholder="{{ __('app.feed.write_comment') }}">
                    <button class="btn btn-primary btn-sm">{{ __('app.feed.send') }}</button>
                </form>
                @foreach($post->comments as $c)
                    <div class="comment-item mt-2">
                        <div class="comment-avatar"></div>
                        <div>
                            <div class="comment-bubble">
                                <div class="comment-author d-flex align-items-center justify-content-between">
                                    <span>{{ $c->user->name ?? 'User' }}</span>
                                    @auth
                                    @php $canEditComment = (auth()->id() === $c->user_id) || (in_array(auth()->user()->user_role,['admin','super_admin'])); @endphp
                                    @if($canEditComment)
                                    <div class="dropdown ms-2">
                                        <button class="btn btn-sm btn-link text-muted p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editCommentModal{{ $c->id }}">
                                                    <i class="fas fa-pen me-2"></i>Chỉnh sửa
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="{{ route('comments.delete',$c) }}" class="m-0" onsubmit="return confirm('Xóa bình luận này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash me-2"></i>Xóa</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                    @endauth
                                </div>
                                <div class="comment-text">{{ $c->content }}</div>
                            </div>
                            <div class="comment-actions position-relative">
                                <span class="comment-time">{{ $c->created_at->diffForHumans() }}</span>
                                @php
                                  $myCReaction = auth()->check() ? optional($c->reactions->firstWhere('user_id', auth()->id()))->type : null;
                                  $cIconMap = ['like'=>'far fa-thumbs-up','love'=>'fas fa-heart','haha'=>'fas fa-laugh','wow'=>'fas fa-surprise','sad'=>'fas fa-sad-tear','angry'=>'fas fa-angry'];
                                  $cTextMap = ['like'=>'Thích','love'=>'Yêu thích','haha'=>'Haha','wow'=>'Wow','sad'=>'Buồn','angry'=>'Phẫn nộ'];
                                  $cType = $myCReaction ?: ( ($c->likes_count ?? 0) > 0 ? 'like' : null);
                                  $cLabel = $myCReaction ? ($cTextMap[$myCReaction] ?? 'Thích') : 'Thích';
                                @endphp
                                <span class="comment-action comment-like-btn {{ $cType ? 'text-primary' : '' }}"
                                      data-like-endpoint="{{ route('comments.like',$c) }}"
                                      data-react-endpoint="{{ route('comments.react',$c) }}"
                                      data-comment-id="{{ $c->id }}"
                                      data-reaction="{{ $myCReaction ?? '' }}">
                                      <span id="c-like-label-{{ $c->id }}">{{ $cLabel }}</span>
                                </span>
                                <div class="c-react-pop" data-for-comment="{{ $c->id }}">
                                    <div class="r like" data-type="like"><i class="far fa-thumbs-up"></i></div>
                                    <div class="r love" data-type="love"><i class="fas fa-heart"></i></div>
                                    <div class="r haha" data-type="haha"><i class="fas fa-laugh"></i></div>
                                    <div class="r wow" data-type="wow"><i class="fas fa-surprise"></i></div>
                                    <div class="r sad" data-type="sad"><i class="fas fa-sad-tear"></i></div>
                                    <div class="r angry" data-type="angry"><i class="fas fa-angry"></i></div>
                                </div>
                                <span class="comment-action">Trả lời</span>
                                <span class="comment-reaction-summary {{ ($c->likes_count ?? 0) > 0 ? '' : 'd-none' }}" id="c-react-summary-{{ $c->id }}">
                                    <span class="c-like-icon c-like-{{ $cType ?? 'like' }}" id="c-react-summary-icon-{{ $c->id }}"><i class="{{ $cIconMap[$cType ?? 'like'] }}"></i></span>
                                    <span id="c-react-summary-count-{{ $c->id }}">{{ $c->likes_count ?? 0 }}</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Comment Modal -->
                    <div class="modal fade" id="editCommentModal{{ $c->id }}" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Chỉnh sửa bình luận</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <form method="POST" action="{{ route('comments.update',$c) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                              <textarea name="content" class="form-control" rows="3">{{ old('content',$c->content) }}</textarea>
                            </div>
                            <div class="modal-footer">
                              <button class="btn btn-primary">Lưu</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                @endforeach
            </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="feed-wrap">{{ $posts->links() }}</div>
 </div>

@push('scripts')
<script>
(()=>{
  const filesInput = document.getElementById('composerFiles');
  const mediaPreview = document.getElementById('mediaPreview');
  filesInput && filesInput.addEventListener('change', (e)=>{
    mediaPreview.innerHTML='';
    [...e.target.files].forEach(file=>{
      const col = document.createElement('div'); col.className='col-4';
      if(file.type.startsWith('video')){
        const v=document.createElement('video');v.controls=true;v.className='w-100 rounded';
        v.src=URL.createObjectURL(file); col.appendChild(v);
      } else { const img=new Image(); img.className='img-fluid rounded'; img.src=URL.createObjectURL(file); col.appendChild(img); }
      mediaPreview.appendChild(col);
    });
  });

  // Tags
  const tagBtn=document.getElementById('tagBtn'); const tagContainer=document.getElementById('tagContainer');
  tagBtn && tagBtn.addEventListener('click',()=>{ tagContainer.style.display = tagContainer.style.display==='none'?'block':'none'; });
  const tagInput=document.getElementById('tagInput'); const tagResults=document.getElementById('tagResults'); const taggedList=document.getElementById('taggedList'); const mentionsField=document.getElementById('mentionsField');
  const selectedIds=new Set();
  tagInput && tagInput.addEventListener('input', function(){
    const q=this.value.trim(); if(!q){ tagResults.innerHTML=''; return; }
    fetch(`{{ route('profile.search-users') }}?q=${encodeURIComponent(q)}`)
      .then(r=>r.json()).then(data=>{
        tagResults.innerHTML='';
        (data.users||data || []).slice(0,5).forEach(u=>{
          const a=document.createElement('a'); a.className='list-group-item list-group-item-action'; a.textContent=u.name || u.full_name || u.email; a.href='#';
          a.addEventListener('click',e=>{e.preventDefault(); selectedIds.add(u.id); renderTagged(); tagResults.innerHTML=''; tagInput.value='';});
          tagResults.appendChild(a);
        });
      }).catch(()=>{});
  });
  function renderTagged(){
    mentionsField.value=[...selectedIds].join(',');
    taggedList.textContent = mentionsField.value ? ('Đã gắn thẻ: '+mentionsField.value) : '';
  }

  // Visibility selector
  const visBtn = document.getElementById('visibilityBtn');
  const visField = document.getElementById('visibilityField');
  const visInclude = document.getElementById('visibilityIncludeField');
  const visExclude = document.getElementById('visibilityExcludeField');
  if(visBtn){
    visBtn.parentElement.querySelectorAll('.dropdown-item').forEach(it=>{
      it.addEventListener('click', (e)=>{
        e.preventDefault();
        const v = it.getAttribute('data-vis');
        visField.value = v;
        visBtn.textContent = it.textContent.trim();
        // When choose advanced options, open simple prompts for now
        if(v==='friends_except'){
          const ids = prompt('Nhập ID bạn bè loại trừ (ngăn cách bằng dấu phẩy):','');
          visExclude.value = (ids||'').replace(/\s+/g,'');
        } else if(v==='specific_friends' || v==='custom'){
          const ids = prompt('Nhập ID bạn bè được phép (ngăn cách bằng dấu phẩy):','');
          visInclude.value = (ids||'').replace(/\s+/g,'');
        } else {
          visInclude.value = '';
          visExclude.value = '';
        }
      });
    });
  }

  // Like/Reactions UI
  const reactionTextMap = { like:'Thích', love:'Yêu thích', haha:'Haha', wow:'Wow', sad:'Buồn', angry:'Phẫn nộ' };
  const reactionIconMap = { like:'far fa-thumbs-up', love:'fas fa-heart', haha:'fas fa-laugh', wow:'fas fa-surprise', sad:'fas fa-sad-tear', angry:'fas fa-angry' };

  document.querySelectorAll('.like-wrapper').forEach(wrapper=>{
    const button = wrapper.querySelector('.like-btn');
    const pop = wrapper.querySelector('.reactions-popover');
    const postId = button.getAttribute('data-post-id');
    let hideTimer = null;

    function showPop(){ clearTimeout(hideTimer); pop.style.display='flex'; }
    function hidePop(){ hideTimer = setTimeout(()=>{ pop.style.display='none'; }, 180); }

    // Hover to show reactions
    button.addEventListener('mouseenter', showPop);
    button.addEventListener('mouseleave', hidePop);
    pop.addEventListener('mouseenter', showPop);
    pop.addEventListener('mouseleave', hidePop);

    // Click on button: if reaction exists -> remove reaction; if none -> quick like
    button.addEventListener('click', ()=>{
      const current = button.getAttribute('data-reaction');
      if(current){
        // remove any reaction
        updateReaction('');
        optimisticCount(-1);
        sendReact('none');
      } else {
        // quick like
        updateReaction('like');
        optimisticCount(1);
        sendReact('like');
      }
    });

    // Select reaction from popover
    pop.querySelectorAll('.reaction').forEach(el=>{
      el.addEventListener('click', ()=>{
        const type = el.getAttribute('data-type');
        const prev = button.getAttribute('data-reaction');
        if(!prev){ optimisticCount(1); }
        updateReaction(type);
        sendReact(type);
        pop.style.display='none';
      });
    });

    function updateReaction(type){
      const prev = button.getAttribute('data-reaction') || '';
      button.setAttribute('data-reaction', type);
      const icon = button.querySelector('i');
      icon.className = reactionIconMap[type] || 'far fa-thumbs-up';
      button.classList.toggle('active', !!type);
      // remove previous reacted-* class and add new one
      if(prev){ button.classList.remove('reacted-'+prev); }
      if(type){ button.classList.add('reacted-'+type); } else { ['like','love','haha','wow','sad','angry'].forEach(t=>button.classList.remove('reacted-'+t)); }
      button.lastChild.nodeValue = ' ' + (reactionTextMap[type] || 'Thích');

      // Sync small stats icon color and glyph
      const statIconWrap = document.getElementById('likes-icon-'+postId);
      const statCountWrap = document.getElementById('likes-wrap-'+postId);
      if(statIconWrap){
        // reset icon-like-* classes
        ['like','love','haha','wow','sad','angry'].forEach(t=>statIconWrap.classList.remove('icon-like-'+t));
        statIconWrap.classList.add('icon-like-'+(type || 'like'));
        const glyph = statIconWrap.querySelector('i');
        if(glyph){ glyph.className = reactionIconMap[type || 'like']; }
      }
      if(statCountWrap){ statCountWrap.classList.toggle('d-none', (document.getElementById('likes-count-'+postId).textContent||'0')==='0'); }
    }

    function optimisticCount(delta){
      const countEl = document.getElementById('likes-count-'+postId);
      const wrapEl = document.getElementById('likes-wrap-'+postId);
      let count = parseInt(countEl.textContent||'0',10);
      count = Math.max(0, count + delta);
      countEl.textContent = String(count);
      // Toggle visibility when no likes
      if(wrapEl){ wrapEl.classList.toggle('d-none', count === 0); }
    }

    function sendLikeToggle(){
      const url = button.getAttribute('data-like-endpoint');
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      fetch(url, { method:'POST', headers:{ 'X-CSRF-TOKEN': token, 'Accept':'application/json' } }).catch(()=>{});
    }
    function sendReact(type){
      const url = button.getAttribute('data-react-endpoint');
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const form = new FormData(); form.append('type', type);
      fetch(url, { method:'POST', headers:{ 'X-CSRF-TOKEN': token }, body: form }).catch(()=>{});
    }
  });

  // Reactions modal (simple fetch + bootstrap modal)
  document.querySelectorAll('[data-reactions-modal]').forEach(el=>{
    el.addEventListener('click', function(e){
      e.preventDefault();
      const postId = this.getAttribute('data-reactions-modal');
      const url = `{{ url('/posts') }}/${postId}/reactions`;
      fetch(url, { headers:{ 'Accept':'application/json' } })
        .then(r=>r.json())
        .then(payload=>{
          showReactionsModal(postId, payload);
        });
    });
  });

  function ensureModal(){
    let modal = document.getElementById('reactionsModal');
    if(modal) return modal;
    const tpl = document.createElement('div');
    tpl.innerHTML = `
    <div class="modal fade reactions-modal" id="reactionsModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header"><h5 class="modal-title" id="postModalTitle">Post details</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <ul class="nav nav-pills mb-3" id="reactionsTabs"></ul>
            <div id="reactionsModalBody"></div>
          </div>
        </div>
      </div>
    </div>`;
    document.body.appendChild(tpl.firstElementChild);
    return document.getElementById('reactionsModal');
  }

  function buildReactionTabs(postId, counts){
    const tabs = document.getElementById('reactionsTabs');
    const typeOrder = ['all','like','love','haha','wow','sad','angry'];
    const icons = { all:'fas fa-layer-group', like:'far fa-thumbs-up', love:'fas fa-heart', haha:'fas fa-laugh', wow:'fas fa-surprise', sad:'fas fa-sad-tear', angry:'fas fa-angry' };
    const present = typeOrder.filter(t=> t==='all' || counts[t]);
    tabs.innerHTML = present.map((t,idx)=>{
      const c = t==='all' ? Object.values(counts||{}).reduce((a,b)=>a+(b||0),0) : (counts[t]||0);
      const active = idx===0 ? 'active' : '';
      return `<li class="nav-item"><a href="#" class="nav-link ${active}" data-tab-type="${t}"><i class="${icons[t]}"></i> <span class="ms-1">${c}</span></a></li>`;
    }).join('');
    tabs.querySelectorAll('[data-tab-type]').forEach(a=>{
      a.addEventListener('click', e=>{
        e.preventDefault();
        tabs.querySelectorAll('.nav-link').forEach(x=>x.classList.remove('active'));
        a.classList.add('active');
        const type = a.getAttribute('data-tab-type');
        loadReactions(postId, type==='all'? null : type);
      });
    });
  }

  function loadReactions(postId, type){
    const url = type ? `{{ url('/posts') }}/${postId}/reactions?tab=${encodeURIComponent(type)}` : `{{ url('/posts') }}/${postId}/reactions`;
    fetch(url, { headers:{ 'Accept':'application/json' } })
      .then(r=>r.json())
      .then(payload=>{
        renderReactionsList(payload);
      });
  }

  function renderReactionsList(payload){
    const modal = ensureModal();
    const body = modal.querySelector('#reactionsModalBody');
    const users = (payload && payload.data) || [];
    body.innerHTML = users.map(u=>{
      const avatar = (u.user && u.user.avatar) || "{{ asset('images/default-avatar.png') }}";
      const name = u.user && (u.user.name || 'User');
      return `<div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <div class="d-flex align-items-center gap-2">
          <img src="${avatar}" class="rounded-circle" style="width:40px;height:40px;object-fit:cover"/>
          <a href="{{ url('/profile/user') }}/${u.user.id}" class="text-decoration-none">${name}</a>
        </div>
        <button class="btn btn-sm btn-outline-primary">Theo dõi</button>
      </div>`;
    }).join('') || '<div class="text-muted">No reactions yet</div>';
  }

  function showReactionsModal(postId, payload){
    const modal = ensureModal();
    const title = modal.querySelector('#postModalTitle'); if(title) title.textContent = 'Reactions';
    buildReactionTabs(postId, payload && payload.counts ? payload.counts : {});
    renderReactionsList(payload);
    new bootstrap.Modal(modal).show();
  }

  // Open post details modal (comments/shares summary)
  document.querySelectorAll('[data-open-post-modal]').forEach(el=>{
    el.addEventListener('click', ()=>{
      const postId = el.getAttribute('data-open-post-modal');
      const modal = ensureModal();
      const title = modal.querySelector('#postModalTitle'); if(title) title.textContent = 'Post details';
      const body = modal.querySelector('#reactionsModalBody');
      // clone the post card content into modal (read-only)
      const card = document.querySelector(`[data-post-card="${postId}"]`);
      if(card){
        const clone = card.cloneNode(true);
        // expand comments section so it is visible for reading
        const cmt = clone.querySelector('[id^="cmt"]');
        if(cmt){ cmt.classList.add('show'); cmt.style.display='block'; }
        body.innerHTML='';
        body.appendChild(clone);
      } else {
        body.innerHTML = '<div class="text-muted">Loading...</div>';
      }
      new bootstrap.Modal(modal).show();
    });
  });

  // Open shares list when clicking shares count
  document.querySelectorAll('.shares-link, [data-open-shares]').forEach(el=>{
    el.addEventListener('click', (e)=>{
      e.stopPropagation();
      const parent = el.closest('[data-post-card]') || document;
      const postId = el.getAttribute('data-open-shares') || el.getAttribute('data-open-post-modal') || parent.getAttribute('data-post-card');
      const modal = ensureModal();
      const title = modal.querySelector('#postModalTitle'); if(title) title.textContent = 'Shares';
      const body = modal.querySelector('#reactionsModalBody');
      body.innerHTML = '<div class="text-muted">Loading shares...</div>';
      fetch(`{{ url('/posts') }}/${postId}/shares`, { headers:{ 'Accept':'application/json' } })
        .then(r=>r.json()).then(payload=>{
          const arr = payload.data || [];
          if(!arr.length){ body.innerHTML = '<div class="text-muted">No public shares to show.</div>'; return; }
          body.innerHTML = arr.map(s=>`<div class=\"d-flex align-items-center gap-2 mb-2\"><img src=\"${s.user.avatar}\" class=\"rounded-circle\" style=\"width:36px;height:36px\"/> <div><strong>${s.user.name}</strong><div class=\"text-muted\">${s.created_at}</div></div></div>`).join('');
        }).catch(()=>{ body.innerHTML = '<div class="text-muted">No public shares to show.</div>'; });
      new bootstrap.Modal(modal).show();
    });
  });

  // Auto-refresh counters for each post
  function refreshPostCounters(){
    document.querySelectorAll('[data-post-id]').forEach(btn=>{
      const postId = btn.getAttribute('data-post-id');
      fetch(`{{ url('/posts') }}/${postId}/counters`,{ headers:{ 'Accept':'application/json' }})
        .then(r=>r.json())
        .then(data=>{
          // likes count
          const likeWrap = document.getElementById('likes-wrap-'+postId);
          const likeCountEl = document.getElementById('likes-count-'+postId);
          const counts = data.counts || {}; const top = data.top_types || [];
          if(likeWrap){ likeWrap.classList.toggle('d-none', (counts.likes_count||0) === 0); }
          if(likeCountEl){ likeCountEl.textContent = String(counts.likes_count||0); }
          // update top icons
          const iconsContainer = likeWrap ? likeWrap.querySelector('.counts-icons') : null;
          if(iconsContainer){
            // remove any fallback default icon
            const fallback = likeWrap.querySelector('.fallback-like');
            if(fallback){ fallback.remove(); }
            iconsContainer.innerHTML = '';
            top.forEach(t=>{
              const span = document.createElement('span');
              span.className = 'icon-like icon-like-'+t;
              const i = document.createElement('i');
              i.className = { like:'far fa-thumbs-up', love:'fas fa-heart', haha:'fas fa-laugh', wow:'fas fa-surprise', sad:'fas fa-sad-tear', angry:'fas fa-angry' }[t] || 'far fa-thumbs-up';
              span.appendChild(i);
              iconsContainer.appendChild(span);
            });
          }
          // comments/shares text
          const cmtSpan = document.getElementById('comments-count-'+postId);
          if(cmtSpan){ cmtSpan.textContent = String(counts.comments_count||0); }
          const shareSpan = document.getElementById('shares-count-'+postId);
          if(shareSpan){ shareSpan.textContent = String(counts.shares_count||0); }
        }).catch(()=>{});
    });
  }
  // Immediate refresh on load
  refreshPostCounters();
  // Poll every 3s for snappier updates
  let countersTimer = setInterval(refreshPostCounters, 3000);
  // Pause when tab hidden, resume when visible
  document.addEventListener('visibilitychange', ()=>{
    if(document.hidden){ clearInterval(countersTimer); }
    else { refreshPostCounters(); countersTimer = setInterval(refreshPostCounters, 3000); }
  });

  // Auto-update relative times for posts
  function refreshPostTimes(){
    const now = Date.now();
    document.querySelectorAll('.post-time[data-timestamp]').forEach(el=>{
      const ts = Date.parse(el.getAttribute('data-timestamp'));
      const diffSec = Math.max(1, Math.floor((now - ts)/1000));
      let text;
      if(diffSec < 60) text = `${diffSec} seconds ago`;
      else if(diffSec < 3600) text = `${Math.floor(diffSec/60)} minutes ago`;
      else if(diffSec < 86400) text = `${Math.floor(diffSec/3600)} hours ago`;
      else text = `${Math.floor(diffSec/86400)} days ago`;
      el.textContent = text;
    });
  }
  refreshPostTimes();
  setInterval(refreshPostTimes, 60000);

  // Comment like toggle (no page reload)
  document.querySelectorAll('.comment-actions').forEach(container=>{
    const btn = container.querySelector('.comment-like-btn');
    const pop = container.querySelector('.c-react-pop');
    const commentId = btn.getAttribute('data-comment-id');
    let hideTimer=null;

    function show(){ clearTimeout(hideTimer); pop.style.display='flex'; }
    function hide(){ hideTimer=setTimeout(()=>{ pop.style.display='none'; }, 180); }

    btn.addEventListener('mouseenter', show);
    btn.addEventListener('mouseleave', hide);
    pop.addEventListener('mouseenter', show);
    pop.addEventListener('mouseleave', hide);

    // Click: toggle like if none, else remove reaction
    btn.addEventListener('click', ()=>{
      const current = btn.getAttribute('data-reaction');
      if(current){ // remove
        optimisticCommentCount(commentId, -1);
        sendCommentReact(btn, 'none');
        btn.setAttribute('data-reaction','');
        btn.classList.remove('text-primary');
        const sIcon = document.getElementById('c-react-summary-icon-'+commentId);
        if(sIcon){
          ['like','love','haha','wow','sad','angry'].forEach(t=>sIcon.classList.remove('c-like-'+t));
          sIcon.classList.add('c-like-like');
          const i = sIcon.querySelector('i'); if(i){ i.className = 'far fa-thumbs-up'; }
        }
        const summary = document.getElementById('c-react-summary-'+commentId);
        if(summary){ summary.classList.add('d-none'); }
        const label = document.getElementById('c-like-label-'+commentId);
        if(label){ label.textContent = 'Thích'; }
      } else { // quick like
        optimisticCommentCount(commentId, 1);
        sendCommentReact(btn, 'like');
        btn.setAttribute('data-reaction','like');
        btn.classList.add('text-primary');
        const sIcon = document.getElementById('c-react-summary-icon-'+commentId);
        if(sIcon){
          ['like','love','haha','wow','sad','angry'].forEach(t=>sIcon.classList.remove('c-like-'+t));
          sIcon.classList.add('c-like-like');
          const i = sIcon.querySelector('i'); if(i){ i.className = 'far fa-thumbs-up'; }
        }
        const summary = document.getElementById('c-react-summary-'+commentId);
        if(summary){ summary.classList.remove('d-none'); }
        const label = document.getElementById('c-like-label-'+commentId);
        if(label){ label.textContent = 'Thích'; }
      }
    });

    pop.querySelectorAll('.r').forEach(r=>{
      r.addEventListener('click', ()=>{
        const type = r.getAttribute('data-type');
        const prev = btn.getAttribute('data-reaction');
        if(!prev){ optimisticCommentCount(commentId, 1); }
        btn.setAttribute('data-reaction', type);
        btn.classList.add('text-primary');
        sendCommentReact(btn, type);
        const sIcon = document.getElementById('c-react-summary-icon-'+commentId);
        if(sIcon){
          ['like','love','haha','wow','sad','angry'].forEach(t=>sIcon.classList.remove('c-like-'+t));
          sIcon.classList.add('c-like-'+type);
          const i = sIcon.querySelector('i'); if(i){
            const map = { like:'far fa-thumbs-up', love:'fas fa-heart', haha:'fas fa-laugh', wow:'fas fa-surprise', sad:'fas fa-sad-tear', angry:'fas fa-angry' };
            i.className = map[type] || 'far fa-thumbs-up';
          }
        }
        pop.style.display='none';
        const summary = document.getElementById('c-react-summary-'+commentId);
        if(summary){ summary.classList.remove('d-none'); }
        const label = document.getElementById('c-like-label-'+commentId);
        if(label){
          const txt = { like:'Thích', love:'Yêu thích', haha:'Haha', wow:'Wow', sad:'Buồn', angry:'Phẫn nộ' };
          label.textContent = txt[type] || 'Thích';
        }
      });
    });
  });

  function sendCommentReact(btn, type){
    const url = btn.getAttribute('data-react-endpoint') || btn.getAttribute('data-like-endpoint');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const form = new FormData(); form.append('type', type);
    fetch(url, { method:'POST', headers:{ 'X-CSRF-TOKEN': token }, body: form }).catch(()=>{});
  }

  function optimisticCommentCount(commentId, delta){
    const summary = document.getElementById('c-react-summary-'+commentId);
    const summaryCount = document.getElementById('c-react-summary-count-'+commentId);
    let count = parseInt((summaryCount && summaryCount.textContent)||'0',10);
    count = Math.max(0, count + delta);
    if(summary){ summary.classList.toggle('d-none', count === 0); }
    if(summaryCount){ summaryCount.textContent = String(count); }
  }

  // Modern confirm for deleting posts
  document.querySelectorAll('.delete-post-form').forEach(form=>{
    form.addEventListener('submit', function(e){
      e.preventDefault();
      showDeleteConfirm(()=>{ form.submit(); });
    });
  });

  function ensureConfirmModal(){
    let modal = document.getElementById('confirmModal');
    if(modal) return modal;
    const tpl = document.createElement('div');
    tpl.innerHTML = `
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px">
          <div class="modal-header border-0">
            <h5 class="modal-title">Xóa bài viết</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="d-flex align-items-start gap-3">
              <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px"><i class="fas fa-trash"></i></div>
              <div>
                <div class="fw-semibold">Bạn có chắc muốn xóa bài viết này?</div>
                <div class="text-muted small">Hành động này không thể hoàn tác.</div>
              </div>
            </div>
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
          </div>
        </div>
      </div>
    </div>`;
    document.body.appendChild(tpl.firstElementChild);
    return document.getElementById('confirmModal');
  }

  function showDeleteConfirm(onConfirm){
    const modal = ensureConfirmModal();
    const bsModal = new bootstrap.Modal(modal);
    const btn = modal.querySelector('#confirmDeleteBtn');
    const handler = ()=>{ onConfirm && onConfirm(); bsModal.hide(); btn.removeEventListener('click', handler); };
    btn.addEventListener('click', handler);
    bsModal.show();
  }
})();
</script>
@endpush
@endsection


