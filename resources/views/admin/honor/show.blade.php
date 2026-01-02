@extends('layouts.app')

@section('title', 'Chi tiết đợt vote - ' . $honorEvent->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-eye me-2"></i>Chi tiết đợt vote: {{ $honorEvent->name }}
                    </h6>
                    <div class="btn-group">
                        <a href="{{ route('admin.honor.edit', $honorEvent) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Chỉnh sửa
                        </a>
                        <button type="button" class="btn btn-{{ $honorEvent->is_active ? 'secondary' : 'success' }} btn-sm" 
                                onclick="toggleStatus({{ $honorEvent->id }})">
                            <i class="fas fa-{{ $honorEvent->is_active ? 'pause' : 'play' }} me-1"></i>
                            {{ $honorEvent->is_active ? 'Tắt' : 'Bật' }}
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" 
                                onclick="resetVotes({{ $honorEvent->id }})">
                            <i class="fas fa-trash me-1"></i>Reset vote
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Event Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Thông tin cơ bản
                                    </div>
                                    <div class="h6 mb-0 text-gray-800">
                                        <strong>Tên:</strong> {{ $honorEvent->name }}
                                    </div>
                                    @if($honorEvent->description)
                                        <div class="h6 mb-0 text-gray-800">
                                            <strong>Mô tả:</strong> {{ $honorEvent->description }}
                                        </div>
                                    @endif
                                    <div class="h6 mb-0 text-gray-800">
                                        <strong>Chế độ:</strong> 
                                        <span class="badge badge-{{ $honorEvent->mode === 'event' ? 'warning' : 'success' }}">
                                            {{ $honorEvent->mode === 'event' ? 'Sự kiện' : 'Tự do' }}
                                        </span>
                                    </div>
                                    <div class="h6 mb-0 text-gray-800">
                                        <strong>Đối tượng:</strong> 
                                        <span class="badge badge-info">{{ ucfirst($honorEvent->target_type) }}</span>
                                    </div>
                                    <div class="h6 mb-0 text-gray-800">
                                        <strong>Trạng thái:</strong> 
                                        @if($honorEvent->isCurrentlyRunning())
                                            <span class="badge badge-success">Đang diễn ra</span>
                                        @else
                                            <span class="badge badge-{{ $honorEvent->is_active ? 'warning' : 'secondary' }}">
                                                {{ $honorEvent->is_active ? 'Tạm dừng' : 'Tắt' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-left-success">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Thời gian & Thống kê
                                    </div>
                                    @if($honorEvent->start_time)
                                        <div class="h6 mb-0 text-gray-800">
                                            <strong>Bắt đầu:</strong> {{ $honorEvent->start_time->format('d/m/Y H:i:s') }}
                                        </div>
                                    @endif
                                    @if($honorEvent->end_time)
                                        <div class="h6 mb-0 text-gray-800">
                                            <strong>Kết thúc:</strong> {{ $honorEvent->end_time->format('d/m/Y H:i:s') }}
                                        </div>
                                    @endif
                                    <div class="h6 mb-0 text-gray-800">
                                        <strong>Tổng vote:</strong> {{ $stats['total_votes'] }}
                                    </div>
                                    <div class="h6 mb-0 text-gray-800">
                                        <strong>Trọng số tổng:</strong> {{ number_format($stats['total_weighted_votes'], 1) }}
                                    </div>
                                    <div class="h6 mb-0 text-gray-800">
                                        <strong>Tạo bởi:</strong> {{ $honorEvent->creator->name ?? 'Unknown' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vote Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-gray-800 mb-3">Cài đặt vote</h6>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6 class="text-primary">Quyền vote</h6>
                                    <div class="mb-2">
                                        <span class="badge badge-{{ $honorEvent->allow_participant_vote ? 'success' : 'secondary' }}">
                                            Participant {{ $honorEvent->allow_participant_vote ? '✓' : '✗' }}
                                        </span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge badge-{{ $honorEvent->allow_admin_vote ? 'success' : 'secondary' }}">
                                            Admin {{ $honorEvent->allow_admin_vote ? '✓' : '✗' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6 class="text-primary">Trọng số</h6>
                                    <div class="mb-2">
                                        <strong>Participant:</strong> {{ $honorEvent->participant_weight }}x
                                    </div>
                                    <div class="mb-2">
                                        <strong>Admin:</strong> {{ $honorEvent->admin_weight }}x
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6 class="text-primary">Tùy chọn</h6>
                                    <div class="mb-2">
                                        <span class="badge badge-{{ $honorEvent->allow_anonymous ? 'success' : 'secondary' }}">
                                            Vote ẩn danh {{ $honorEvent->allow_anonymous ? '✓' : '✗' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vote Statistics by Role -->
                    @if($stats['votes_by_role']->count() > 0)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-gray-800 mb-3">Thống kê vote theo role</h6>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Role</th>
                                                <th>Số lượng vote</th>
                                                <th>Tổng trọng số</th>
                                                <th>Tỷ lệ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($stats['votes_by_role'] as $roleStat)
                                                <tr>
                                                    <td>
                                                        <span class="badge badge-info">{{ ucfirst($roleStat->voter_role) }}</span>
                                                    </td>
                                                    <td>{{ $roleStat->count }}</td>
                                                    <td>{{ number_format($roleStat->total_weight, 1) }}</td>
                                                    <td>
                                                        @if($stats['total_weighted_votes'] > 0)
                                                            {{ number_format(($roleStat->total_weight / $stats['total_weighted_votes']) * 100, 1) }}%
                                                        @else
                                                            0%
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Top Voted Items -->
                    @if($stats['top_items']->count() > 0)
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-gray-800 mb-3">Top {{ $stats['top_items']->count() }} được vote nhiều nhất</h6>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="40%">{{ ucfirst($honorEvent->target_type) }}</th>
                                                <th width="15%">Số vote</th>
                                                <th width="15%">Trọng số</th>
                                                <th width="15%">Tỷ lệ</th>
                                                <th width="10%">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $rank = 1; @endphp
                                            @foreach($stats['top_items'] as $item)
                                                <tr class="{{ $rank <= 3 ? 'table-' . ($rank === 1 ? 'success' : ($rank === 2 ? 'warning' : 'info')) : '' }}">
                                                    <td>{{ $rank }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if($honorEvent->target_type === 'user')
                                                                <img src="{{ get_avatar_url($item['item']->avatar) }}" 
                                                                     class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                                                            @elseif($honorEvent->target_type === 'team')
                                                                <img src="{{ $item['item']->logo ? asset('storage/' . $item['item']->logo) : asset('images/default-team.png') }}" 
                                                                     class="rounded me-3" width="40" height="40" alt="Logo">
                                                            @elseif($honorEvent->target_type === 'tournament')
                                                                <div class="bg-primary text-white rounded me-3 d-flex align-items-center justify-content-center" 
                                                                     style="width: 40px; height: 40px;">
                                                                    <i class="fas fa-trophy"></i>
                                                                </div>
                                                            @elseif($honorEvent->target_type === 'game')
                                                                <img src="{{ $item['item']->image ? asset('storage/' . $item['item']->image) : asset('images/default-game.png') }}" 
                                                                     class="rounded me-3" width="40" height="40" alt="Game">
                                                            @endif
                                                            <div>
                                                                <strong>{{ $item['item_name'] }}</strong>
                                                                @if($honorEvent->target_type === 'tournament' && $item['item']->prize_pool)
                                                                    <br><small class="text-success">{{ number_format($item['item']->prize_pool) }} VNĐ</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-primary">{{ $item['vote_count'] }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-success">{{ number_format($item['total_weight'], 1) }}</span>
                                                    </td>
                                                    <td>
                                                        @if($stats['total_weighted_votes'] > 0)
                                                            <div class="progress" style="height: 20px;">
                                                                <div class="progress-bar" role="progressbar" 
                                                                     style="width: {{ ($item['total_weight'] / $stats['total_weighted_votes']) * 100 }}%"
                                                                     aria-valuenow="{{ $item['total_weight'] }}" 
                                                                     aria-valuemin="0" 
                                                                     aria-valuemax="{{ $stats['total_weighted_votes'] }}">
                                                                    {{ number_format(($item['total_weight'] / $stats['total_weighted_votes']) * 100, 1) }}%
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">0%</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('honor.results', $honorEvent) }}" 
                                                           class="btn btn-sm btn-outline-primary" title="Xem kết quả">
                                                            <i class="fas fa-chart-bar"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @php $rank++; @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có vote nào</h5>
                            <p class="text-muted">Hãy quay lại sau để xem thống kê!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Status Form -->
<form id="toggleForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<!-- Reset Votes Form -->
<form id="resetForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function toggleStatus(eventId) {
    if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái đợt vote này?')) {
        const form = document.getElementById('toggleForm');
        form.action = `/admin/honor/${eventId}/toggle`;
        form.submit();
    }
}

function resetVotes(eventId) {
    if (confirm('Bạn có chắc chắn muốn reset tất cả vote trong đợt này? Hành động này không thể hoàn tác!')) {
        const form = document.getElementById('resetForm');
        form.action = `/admin/honor/${eventId}/reset`;
        form.submit();
    }
}
</script>
@endpush
