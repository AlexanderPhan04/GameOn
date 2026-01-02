@extends('layouts.app')

@section('title', 'Vote - ' . $honorEvent->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-vote-yea me-2"></i>{{ $honorEvent->name }}
                    </h6>
                </div>
                <div class="card-body">
                    @if($honorEvent->description)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>{{ $honorEvent->description }}
                        </div>
                    @endif

                    <!-- Event Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Thông tin đợt vote
                                    </div>
                                    <div class="h6 mb-0 text-gray-800">
                                        <strong>Chế độ:</strong> {{ $honorEvent->mode === 'event' ? 'Sự kiện' : 'Tự do' }}
                                    </div>
                                    <div class="h6 mb-0 text-gray-800">
                                        <strong>Đối tượng:</strong> {{ ucfirst($honorEvent->target_type) }}
                                    </div>
                                    @if($honorEvent->start_time)
                                        <div class="h6 mb-0 text-gray-800">
                                            <strong>Bắt đầu:</strong> {{ $honorEvent->start_time->format('d/m/Y H:i') }}
                                        </div>
                                    @endif
                                    @if($honorEvent->end_time)
                                        <div class="h6 mb-0 text-gray-800">
                                            <strong>Kết thúc:</strong> {{ $honorEvent->end_time->format('d/m/Y H:i') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-left-success">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Thống kê
                                    </div>
                                    <div class="h6 mb-0 text-gray-800">
                                        <strong>Tổng vote:</strong> {{ $honorEvent->getTotalVotesCount() }}
                                    </div>
                                    <div class="h6 mb-0 text-gray-800">
                                        <strong>Trọng số tổng:</strong> {{ number_format($honorEvent->getTotalWeightedVotes(), 1) }}
                                    </div>
                                    <div class="h6 mb-0 text-gray-800">
                                        <strong>Trọng số của bạn:</strong> {{ $honorEvent->getWeightForRole(auth()->user()->user_role) }}x
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Vote -->
                    @if($userVote)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Bạn đã vote cho:</strong> {{ $userVote->getVotedItemName() }}
                            @if($userVote->comment)
                                <br><small class="text-muted">Bình luận: "{{ $userVote->comment }}"</small>
                            @endif
                            <br><small class="text-muted">Vote lúc: {{ $userVote->created_at->format('d/m/Y H:i:s') }}</small>
                        </div>
                    @endif

                    <!-- Vote Form -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-gray-800 mb-3">
                                <i class="fas fa-list me-2"></i>Chọn {{ ucfirst($honorEvent->target_type) }} để vote
                            </h5>
                        </div>
                    </div>

                    @if($votableItems->count() > 0)
                        <div class="row">
                            @foreach($votableItems as $item)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100 vote-item" data-item-id="{{ $item->id }}">
                                        <div class="card-body text-center">
                                            @if($honorEvent->target_type === 'user')
                                                <img src="{{ get_avatar_url($item->avatar) }}" 
                                                     class="rounded-circle mb-3" width="80" height="80" alt="Avatar">
                                                <h6 class="card-title">{{ $item->name ?? $item->display_name }}</h6>
                                            @elseif($honorEvent->target_type === 'team')
                                                <img src="{{ $item->logo ? asset('storage/' . $item->logo) : asset('images/default-team.png') }}" 
                                                     class="rounded mb-3" width="80" height="80" alt="Logo">
                                                <h6 class="card-title">{{ $item->name }}</h6>
                                                @if($item->description)
                                                    <p class="card-text text-muted small">{{ Str::limit($item->description, 100) }}</p>
                                                @endif
                                            @elseif($honorEvent->target_type === 'tournament')
                                                <div class="bg-primary text-white rounded mb-3 p-3">
                                                    <i class="fas fa-trophy fa-2x"></i>
                                                </div>
                                                <h6 class="card-title">{{ $item->name }}</h6>
                                                @if($item->prize_pool)
                                                    <p class="text-success font-weight-bold">Giải thưởng: {{ number_format($item->prize_pool) }} VNĐ</p>
                                                @endif
                                            @elseif($honorEvent->target_type === 'game')
                                                <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('images/default-game.png') }}" 
                                                     class="rounded mb-3" width="80" height="80" alt="Game">
                                                <h6 class="card-title">{{ $item->name }}</h6>
                                                @if($item->description)
                                                    <p class="card-text text-muted small">{{ Str::limit($item->description, 100) }}</p>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="card-footer bg-transparent text-center">
                                            <button class="btn btn-primary vote-btn" data-item-id="{{ $item->id }}">
                                                <i class="fas fa-vote-yea me-1"></i>Vote
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Không có {{ $honorEvent->target_type }} nào để vote</h5>
                            <p class="text-muted">Hãy quay lại sau!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vote Modal -->
<div class="modal fade" id="voteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận vote</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="voteForm">
                    @csrf
                    <input type="hidden" id="votedItemId" name="voted_item_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Bạn đang vote cho:</label>
                        <div id="votedItemName" class="form-control-plaintext font-weight-bold"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="comment" class="form-label">Bình luận (tùy chọn)</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" 
                                  placeholder="Nhập bình luận của bạn..."></textarea>
                    </div>
                    
                    @if($honorEvent->allow_anonymous)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_anonymous" name="is_anonymous">
                            <label class="form-check-label" for="is_anonymous">
                                Vote ẩn danh
                            </label>
                        </div>
                    @endif
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="confirmVote">Xác nhận vote</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const voteButtons = document.querySelectorAll('.vote-btn');
    const voteModal = new bootstrap.Modal(document.getElementById('voteModal'));
    const voteForm = document.getElementById('voteForm');
    const confirmVoteBtn = document.getElementById('confirmVote');
    
    voteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            const card = this.closest('.vote-item');
            const itemName = card.querySelector('.card-title').textContent;
            
            document.getElementById('votedItemId').value = itemId;
            document.getElementById('votedItemName').textContent = itemName;
            
            voteModal.show();
        });
    });
    
    confirmVoteBtn.addEventListener('click', function() {
        const formData = new FormData(voteForm);
        
        fetch(`{{ route('honor.vote', $honorEvent) }}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                voteModal.hide();
                location.reload();
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi vote!');
        });
    });
});
</script>
@endpush
