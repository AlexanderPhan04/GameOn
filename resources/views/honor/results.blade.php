@extends('layouts.app')

@section('title', 'Kết quả - ' . $honorEvent->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Kết quả vote: {{ $honorEvent->name }}
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
                        <div class="col-md-3">
                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Tổng vote
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $honorEvent->getTotalVotesCount() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-success">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Trọng số tổng
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($honorEvent->getTotalWeightedVotes(), 1) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-info">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Chế độ
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $honorEvent->mode === 'event' ? 'Sự kiện' : 'Tự do' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-warning">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Trạng thái
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        @if($honorEvent->isCurrentlyRunning())
                                            <span class="text-success">Đang diễn ra</span>
                                        @else
                                            <span class="text-secondary">Đã kết thúc</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Results -->
                    @if($results->count() > 0)
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-gray-800 mb-3">
                                    <i class="fas fa-trophy me-2"></i>Bảng xếp hạng
                                </h5>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="15%">Hạng</th>
                                        <th width="40%">{{ ucfirst($honorEvent->target_type) }}</th>
                                        <th width="15%">Tổng vote</th>
                                        <th width="15%">Trọng số</th>
                                        <th width="10%">Tỷ lệ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalWeight = $results->sum('total_weight');
                                        $rank = 1;
                                    @endphp
                                    @foreach($results as $result)
                                        <tr class="{{ $rank <= 3 ? 'table-' . ($rank === 1 ? 'success' : ($rank === 2 ? 'warning' : 'info')) : '' }}">
                                            <td>{{ $rank }}</td>
                                            <td>
                                                @if($rank === 1)
                                                    <i class="fas fa-crown text-warning"></i> 🥇
                                                @elseif($rank === 2)
                                                    <i class="fas fa-medal text-secondary"></i> 🥈
                                                @elseif($rank === 3)
                                                    <i class="fas fa-award text-warning"></i> 🥉
                                                @else
                                                    <span class="badge badge-secondary">{{ $rank }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($honorEvent->target_type === 'user')
                                                        <img src="{{ get_avatar_url($result['item']->avatar) }}" 
                                                             class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                                                    @elseif($honorEvent->target_type === 'team')
                                                        <img src="{{ $result['item']->logo ? asset('storage/' . $result['item']->logo) : asset('images/default-team.png') }}" 
                                                             class="rounded me-3" width="40" height="40" alt="Logo">
                                                    @elseif($honorEvent->target_type === 'tournament')
                                                        <div class="bg-primary text-white rounded me-3 d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <i class="fas fa-trophy"></i>
                                                        </div>
                                                    @elseif($honorEvent->target_type === 'game')
                                                        <img src="{{ $result['item']->image ? asset('storage/' . $result['item']->image) : asset('images/default-game.png') }}" 
                                                             class="rounded me-3" width="40" height="40" alt="Game">
                                                    @endif
                                                    <div>
                                                        <strong>{{ $result['item_name'] }}</strong>
                                                        @if($honorEvent->target_type === 'tournament' && $result['item']->prize_pool)
                                                            <br><small class="text-success">{{ number_format($result['item']->prize_pool) }} VNĐ</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">{{ $result['vote_count'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">{{ number_format($result['total_weight'], 1) }}</span>
                                            </td>
                                            <td>
                                                @if($totalWeight > 0)
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar" role="progressbar" 
                                                             style="width: {{ ($result['total_weight'] / $totalWeight) * 100 }}%"
                                                             aria-valuenow="{{ $result['total_weight'] }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="{{ $totalWeight }}">
                                                            {{ number_format(($result['total_weight'] / $totalWeight) * 100, 1) }}%
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">0%</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @php $rank++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Charts -->
                        <div class="row mt-4">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            <i class="fas fa-chart-pie me-2"></i>Biểu đồ phân bố vote
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="voteChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            <i class="fas fa-chart-bar me-2"></i>Top 5
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="top5Chart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có vote nào</h5>
                            <p class="text-muted">Hãy quay lại sau để xem kết quả!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($results->count() > 0)
        // Vote Distribution Chart
        const voteCtx = document.getElementById('voteChart').getContext('2d');
        const voteChart = new Chart(voteCtx, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach($results as $result)
                        '{{ $result['item_name'] }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($results as $result)
                            {{ $result['total_weight'] }},
                        @endforeach
                    ],
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                        '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
                    ],
                    hoverBackgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                        '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Top 5 Chart
        const top5Ctx = document.getElementById('top5Chart').getContext('2d');
        const top5Data = @json($results->take(5));
        const top5Chart = new Chart(top5Ctx, {
            type: 'bar',
            data: {
                labels: top5Data.map(item => item.item_name),
                datasets: [{
                    label: 'Trọng số',
                    data: top5Data.map(item => item.total_weight),
                    backgroundColor: '#36A2EB',
                    borderColor: '#36A2EB',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    @endif
});
</script>
@endpush
