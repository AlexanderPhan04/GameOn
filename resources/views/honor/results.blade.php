@extends('layouts.app')

@section('title', 'Kết quả - ' . $honorEvent->name)

@push('styles')
<style>
    .results-container {
        background: #000814;
        min-height: calc(100vh - 64px);
    }

    /* Header */
    .results-header {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(245, 158, 11, 0.2);
        border-radius: 16px;
        position: relative;
        overflow: hidden;
    }

    .results-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, #f59e0b, #00E5FF, transparent);
    }

    .header-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
    }

    .btn-back {
        width: 36px;
        height: 36px;
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #00E5FF;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: rgba(0, 229, 255, 0.2);
        color: #fff;
    }

    /* Stats Bar */
    .stats-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1px;
        background: rgba(0, 229, 255, 0.1);
        border-radius: 12px;
        overflow: hidden;
    }

    .stat-item {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon.cyan { background: rgba(0, 229, 255, 0.15); color: #00E5FF; }
    .stat-icon.green { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
    .stat-icon.amber { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .stat-icon.purple { background: rgba(168, 85, 247, 0.15); color: #a855f7; }

    .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #fff;
    }

    .stat-label {
        font-size: 0.7rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Content Card */
    .content-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.12);
        border-radius: 16px;
        overflow: hidden;
    }

    .card-header-custom {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(245, 158, 11, 0.15);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #f59e0b;
        font-weight: 600;
    }

    /* Ranking Table */
    .ranking-table {
        width: 100%;
        border-collapse: collapse;
    }

    .ranking-table th {
        background: rgba(245, 158, 11, 0.05);
        color: #94a3b8;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.875rem 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(245, 158, 11, 0.1);
    }

    .ranking-table td {
        padding: 0.875rem 1rem;
        color: #e2e8f0;
        font-size: 0.875rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.05);
        vertical-align: middle;
    }

    .ranking-table tbody tr {
        transition: all 0.3s ease;
    }

    .ranking-table tbody tr:hover {
        background: rgba(245, 158, 11, 0.05);
    }

    /* Rank Badges */
    .rank-badge {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
    }

    .rank-1 {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: #000;
        box-shadow: 0 0 15px rgba(245, 158, 11, 0.4);
    }

    .rank-2 {
        background: linear-gradient(135deg, #94a3b8, #64748b);
        color: #fff;
    }

    .rank-3 {
        background: linear-gradient(135deg, #cd7f32, #a0522d);
        color: #fff;
    }

    .rank-default {
        background: rgba(100, 116, 139, 0.2);
        color: #94a3b8;
    }

    /* Item Info */
    .item-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .item-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid rgba(0, 229, 255, 0.2);
    }

    .item-name {
        font-weight: 600;
        color: #fff;
    }

    .item-sub {
        font-size: 0.75rem;
        color: #22c55e;
    }

    /* Vote Badge */
    .vote-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .vote-badge.primary {
        background: rgba(0, 229, 255, 0.15);
        color: #00E5FF;
    }

    .vote-badge.success {
        background: rgba(34, 197, 94, 0.15);
        color: #22c55e;
    }

    /* Progress Bar */
    .progress-custom {
        height: 8px;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar-custom {
        height: 100%;
        background: linear-gradient(90deg, #f59e0b, #00E5FF);
        border-radius: 4px;
        transition: width 0.5s ease;
    }

    .progress-text {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 0.25rem;
    }

    /* Charts */
    .chart-container {
        padding: 1.25rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: rgba(245, 158, 11, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-icon i {
        font-size: 2rem;
        color: #f59e0b;
    }

    /* Top 3 Podium */
    .podium-section {
        padding: 2rem 1.25rem;
        display: flex;
        justify-content: center;
        align-items: flex-end;
        gap: 1rem;
    }

    .podium-item {
        text-align: center;
        flex: 1;
        max-width: 180px;
    }

    .podium-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 0.75rem;
        border: 3px solid;
    }

    .podium-1 .podium-avatar {
        width: 90px;
        height: 90px;
        border-color: #f59e0b;
        box-shadow: 0 0 25px rgba(245, 158, 11, 0.4);
    }

    .podium-2 .podium-avatar {
        border-color: #94a3b8;
    }

    .podium-3 .podium-avatar {
        border-color: #cd7f32;
    }

    .podium-name {
        font-weight: 600;
        color: #fff;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .podium-votes {
        font-size: 0.75rem;
        color: #64748b;
    }

    .podium-rank {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        border-radius: 8px 8px 0 0;
        margin-top: 0.75rem;
    }

    .podium-1 .podium-rank {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: #000;
        height: 80px;
        font-size: 1.5rem;
    }

    .podium-2 .podium-rank {
        background: linear-gradient(135deg, #94a3b8, #64748b);
        color: #fff;
        height: 60px;
        font-size: 1.25rem;
    }

    .podium-3 .podium-rank {
        background: linear-gradient(135deg, #cd7f32, #a0522d);
        color: #fff;
        height: 45px;
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .stats-bar {
            grid-template-columns: repeat(2, 1fr);
        }
        .podium-section {
            flex-direction: column;
            align-items: center;
        }
        .podium-item {
            max-width: 100%;
        }
        .podium-1 .podium-rank,
        .podium-2 .podium-rank,
        .podium-3 .podium-rank {
            height: 50px;
        }
    }

    @media (max-width: 480px) {
        .stats-bar {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="results-container">
    <div class="max-w-6xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="results-header p-4 mb-5">
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() }}" class="btn-back">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div class="header-icon">
                    <i class="fas fa-chart-bar text-white"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-amber-500 font-['Rajdhani']">Kết quả vote: {{ $honorEvent->name }}</h1>
                    @if($honorEvent->description)
                        <p class="text-slate-500 text-xs">{{ $honorEvent->description }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="stats-bar mb-5">
            <div class="stat-item">
                <div class="stat-icon cyan"><i class="fas fa-vote-yea"></i></div>
                <div>
                    <div class="stat-value" id="total-votes">{{ $honorEvent->getTotalVotesCount() }}</div>
                    <div class="stat-label">Tổng vote</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon green"><i class="fas fa-balance-scale"></i></div>
                <div>
                    <div class="stat-value" id="weighted-total">{{ number_format($honorEvent->getTotalWeightedVotes(), 1) }}</div>
                    <div class="stat-label">Trọng số tổng</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon amber"><i class="fas fa-layer-group"></i></div>
                <div>
                    <div class="stat-value">{{ $honorEvent->mode === 'event' ? 'Sự kiện' : 'Tự do' }}</div>
                    <div class="stat-label">Chế độ</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon purple"><i class="fas fa-{{ $honorEvent->isCurrentlyRunning() ? 'check-circle' : 'stop-circle' }}"></i></div>
                <div>
                    <div class="stat-value {{ $honorEvent->isCurrentlyRunning() ? 'text-green-400' : 'text-slate-400' }}" id="event-status">
                        {{ $honorEvent->isCurrentlyRunning() ? 'Đang diễn ra' : 'Đã kết thúc' }}
                    </div>
                    <div class="stat-label">Trạng thái</div>
                </div>
            </div>
        </div>

        @if($results->count() > 0)
            @php
                $totalWeight = $results->sum('total_weight');
                $top3 = $results->take(3);
            @endphp

            <!-- Top 3 Podium -->
            @if($results->count() >= 3)
            <div class="content-card mb-5">
                <div class="card-header-custom">
                    <i class="fas fa-crown"></i>
                    <span>Top 3</span>
                </div>
                <div class="podium-section">
                    <!-- 2nd Place -->
                    <div class="podium-item podium-2">
                        @if($honorEvent->target_type === 'user')
                            <img src="{{ $top3[1]['item']->getDisplayAvatar() }}" class="podium-avatar" alt="">
                        @else
                            <div class="podium-avatar bg-slate-700 flex items-center justify-center">
                                <i class="fas fa-{{ $honorEvent->target_type === 'team' ? 'users' : ($honorEvent->target_type === 'tournament' ? 'trophy' : 'gamepad') }} text-slate-400"></i>
                            </div>
                        @endif
                        <div class="podium-name">{{ $top3[1]['item_name'] }}</div>
                        <div class="podium-votes">{{ $top3[1]['vote_count'] }} votes • {{ number_format($top3[1]['total_weight'], 1) }} điểm</div>
                        <div class="podium-rank">2</div>
                    </div>
                    <!-- 1st Place -->
                    <div class="podium-item podium-1">
                        @if($honorEvent->target_type === 'user')
                            <img src="{{ $top3[0]['item']->getDisplayAvatar() }}" class="podium-avatar" alt="">
                        @else
                            <div class="podium-avatar bg-slate-700 flex items-center justify-center" style="width:90px;height:90px;">
                                <i class="fas fa-{{ $honorEvent->target_type === 'team' ? 'users' : ($honorEvent->target_type === 'tournament' ? 'trophy' : 'gamepad') }} text-2xl text-amber-500"></i>
                            </div>
                        @endif
                        <div class="podium-name">{{ $top3[0]['item_name'] }}</div>
                        <div class="podium-votes">{{ $top3[0]['vote_count'] }} votes • {{ number_format($top3[0]['total_weight'], 1) }} điểm</div>
                        <div class="podium-rank"><i class="fas fa-crown mr-1"></i> 1</div>
                    </div>
                    <!-- 3rd Place -->
                    <div class="podium-item podium-3">
                        @if($honorEvent->target_type === 'user')
                            <img src="{{ $top3[2]['item']->getDisplayAvatar() }}" class="podium-avatar" alt="">
                        @else
                            <div class="podium-avatar bg-slate-700 flex items-center justify-center">
                                <i class="fas fa-{{ $honorEvent->target_type === 'team' ? 'users' : ($honorEvent->target_type === 'tournament' ? 'trophy' : 'gamepad') }} text-slate-400"></i>
                            </div>
                        @endif
                        <div class="podium-name">{{ $top3[2]['item_name'] }}</div>
                        <div class="podium-votes">{{ $top3[2]['vote_count'] }} votes • {{ number_format($top3[2]['total_weight'], 1) }} điểm</div>
                        <div class="podium-rank">3</div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Ranking Table -->
            <div class="content-card mb-5">
                <div class="card-header-custom">
                    <i class="fas fa-list-ol"></i>
                    <span>Bảng xếp hạng đầy đủ</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="ranking-table">
                        <thead>
                            <tr>
                                <th class="w-16">Hạng</th>
                                <th>{{ ucfirst($honorEvent->target_type) }}</th>
                                <th class="w-24 text-center">Votes</th>
                                <th class="w-24 text-center">Điểm</th>
                                <th class="w-40">Tỷ lệ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $rank = 1; @endphp
                            @foreach($results as $result)
                            <tr>
                                <td>
                                    @if($rank === 1)
                                        <span class="rank-badge rank-1"><i class="fas fa-crown"></i></span>
                                    @elseif($rank === 2)
                                        <span class="rank-badge rank-2">2</span>
                                    @elseif($rank === 3)
                                        <span class="rank-badge rank-3">3</span>
                                    @else
                                        <span class="rank-badge rank-default">{{ $rank }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="item-info">
                                        @if($honorEvent->target_type === 'user')
                                            <img src="{{ $result['item']->getDisplayAvatar() }}" class="item-avatar" alt="">
                                        @elseif($honorEvent->target_type === 'team')
                                            <img src="{{ $result['item']->logo ? asset('uploads/' . $result['item']->logo) : asset('images/default-team.png') }}" class="item-avatar" alt="">
                                        @elseif($honorEvent->target_type === 'game')
                                            <img src="{{ $result['item']->image ? asset('uploads/' . $result['item']->image) : asset('images/default-game.png') }}" class="item-avatar" alt="">
                                        @else
                                            <div class="item-avatar bg-slate-700 flex items-center justify-center">
                                                <i class="fas fa-trophy text-amber-500"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="item-name">{{ $result['item_name'] }}</div>
                                            @if($honorEvent->target_type === 'tournament' && $result['item']->prize_pool)
                                                <div class="item-sub">{{ number_format($result['item']->prize_pool) }} VNĐ</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="vote-badge primary">{{ $result['vote_count'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="vote-badge success">{{ number_format($result['total_weight'], 1) }}</span>
                                </td>
                                <td>
                                    @if($totalWeight > 0)
                                        @php $percent = ($result['total_weight'] / $totalWeight) * 100; @endphp
                                        <div class="progress-custom">
                                            <div class="progress-bar-custom" style="width: {{ $percent }}%"></div>
                                        </div>
                                        <div class="progress-text">{{ number_format($percent, 1) }}%</div>
                                    @else
                                        <span class="text-slate-500 text-sm">0%</span>
                                    @endif
                                </td>
                            </tr>
                            @php $rank++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                <div class="content-card">
                    <div class="card-header-custom">
                        <i class="fas fa-chart-pie"></i>
                        <span>Phân bố vote</span>
                    </div>
                    <div class="chart-container">
                        <canvas id="voteChart" height="280"></canvas>
                    </div>
                </div>
                <div class="content-card">
                    <div class="card-header-custom">
                        <i class="fas fa-chart-bar"></i>
                        <span>Top 5</span>
                    </div>
                    <div class="chart-container">
                        <canvas id="top5Chart" height="280"></canvas>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="content-card">
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3 class="text-white text-xl font-semibold mb-2">Chưa có vote nào</h3>
                    <p class="text-slate-500">Hãy quay lại sau để xem kết quả!</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let voteChart, top5Chart;

document.addEventListener('DOMContentLoaded', function() {
    @if($results->count() > 0)
        const chartColors = ['#f59e0b', '#00E5FF', '#22c55e', '#a855f7', '#ef4444', '#ec4899', '#14b8a6', '#f97316', '#6366f1', '#84cc16'];
        
        // Vote Distribution Chart
        const voteCtx = document.getElementById('voteChart').getContext('2d');
        voteChart = new Chart(voteCtx, {
            type: 'doughnut',
            data: {
                labels: [@foreach($results as $result)'{{ $result['item_name'] }}',@endforeach],
                datasets: [{
                    data: [@foreach($results as $result){{ $result['total_weight'] }},@endforeach],
                    backgroundColor: chartColors,
                    borderColor: '#000814',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#94a3b8', padding: 15, font: { size: 11 } }
                    }
                }
            }
        });

        // Top 5 Chart
        const top5Ctx = document.getElementById('top5Chart').getContext('2d');
        const top5Data = @json($results->take(5));
        top5Chart = new Chart(top5Ctx, {
            type: 'bar',
            data: {
                labels: top5Data.map(item => item.item_name),
                datasets: [{
                    label: 'Điểm',
                    data: top5Data.map(item => item.total_weight),
                    backgroundColor: chartColors.slice(0, 5),
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 229, 255, 0.05)' },
                        ticks: { color: '#64748b' }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: '#e2e8f0', font: { size: 11 } }
                    }
                }
            }
        });
    @endif

    // Realtime
    if (typeof Echo !== 'undefined') {
        const eventId = {{ $honorEvent->id }};
        
        Echo.channel('honor.event.' + eventId)
            .listen('.vote.cast', (e) => {
                document.getElementById('total-votes').textContent = e.stats.event_total_votes;
                document.getElementById('weighted-total').textContent = e.stats.event_weighted_total.toFixed(1);
                showToast(`${e.vote.is_anonymous ? 'Ai đó' : e.vote.voter_name} vừa vote!`);
                setTimeout(() => location.reload(), 2000);
            })
            .listen('.votes.reset', () => location.reload());
    }

    function showToast(message) {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed; top: 20px; right: 20px; z-index: 9999;
            padding: 1rem 1.5rem; border-radius: 12px; min-width: 280px;
            background: rgba(0, 229, 255, 0.1); border: 1px solid rgba(0, 229, 255, 0.3); color: #00E5FF;
            display: flex; align-items: center; gap: 10px;
            animation: slideIn 0.3s ease;
        `;
        toast.innerHTML = `<i class="fas fa-vote-yea"></i><span>${message}</span>`;
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.animation = 'slideOut 0.3s ease'; setTimeout(() => toast.remove(), 300); }, 4000);
    }
});
</script>
<style>
@keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
@keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
</style>
@endpush
