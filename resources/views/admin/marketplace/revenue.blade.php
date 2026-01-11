@extends('layouts.app')

@section('title', 'Báo cáo doanh thu Marketplace')

@push('styles')
<style>
    .revenue-container {
        background: #000814;
        min-height: calc(100vh - 64px);
    }

    /* Header */
    .revenue-header {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(34, 197, 94, 0.2);
        border-radius: 16px;
        position: relative;
        overflow: hidden;
    }

    .revenue-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, #22c55e, #00E5FF, transparent);
    }

    .header-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 20px rgba(34, 197, 94, 0.3);
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

    /* Filter Bar */
    .filter-bar {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.12);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-label {
        font-size: 0.8rem;
        color: #94a3b8;
    }

    .filter-input {
        padding: 0.5rem 0.75rem;
        background: rgba(0, 0, 20, 0.5);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 8px;
        color: #fff;
        font-size: 0.85rem;
    }

    .filter-input:focus {
        outline: none;
        border-color: rgba(34, 197, 94, 0.5);
    }

    .btn-filter {
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border: none;
        border-radius: 8px;
        color: #fff;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        box-shadow: 0 0 15px rgba(34, 197, 94, 0.4);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }

    .stat-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.12);
        border-radius: 14px;
        padding: 1.25rem;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }

    .stat-card.green::before { background: #22c55e; }
    .stat-card.cyan::before { background: #00E5FF; }
    .stat-card.amber::before { background: #f59e0b; }
    .stat-card.purple::before { background: #a855f7; }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .stat-icon.green { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
    .stat-icon.cyan { background: rgba(0, 229, 255, 0.15); color: #00E5FF; }
    .stat-icon.amber { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .stat-icon.purple { background: rgba(168, 85, 247, 0.15); color: #a855f7; }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #fff;
        line-height: 1;
    }

    .stat-change {
        font-size: 0.75rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .stat-change.up { color: #22c55e; }
    .stat-change.down { color: #ef4444; }

    /* Content Card */
    .content-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.12);
        border-radius: 16px;
        overflow: hidden;
    }

    .card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(34, 197, 94, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #22c55e;
        font-weight: 600;
    }

    /* Table */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: rgba(34, 197, 94, 0.05);
        color: #94a3b8;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.875rem 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(34, 197, 94, 0.1);
    }

    .data-table td {
        padding: 0.875rem 1rem;
        color: #e2e8f0;
        font-size: 0.875rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.05);
        vertical-align: middle;
    }

    .data-table tbody tr {
        transition: all 0.3s ease;
    }

    .data-table tbody tr:hover {
        background: rgba(34, 197, 94, 0.05);
    }

    /* Order Info */
    .order-code {
        font-weight: 600;
        color: #00E5FF;
        font-family: monospace;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        object-fit: cover;
    }

    .user-name {
        font-weight: 500;
        color: #fff;
    }

    /* Badges */
    .badge {
        padding: 0.25rem 0.625rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-paid { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .badge-pending { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .badge-failed { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    .badge-completed { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .badge-processing { background: rgba(0, 229, 255, 0.2); color: #00E5FF; }

    .amount {
        font-weight: 600;
        color: #22c55e;
    }

    /* Chart Container */
    .chart-container {
        padding: 1.25rem;
        height: 300px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }

    .empty-icon {
        width: 70px;
        height: 70px;
        background: rgba(100, 116, 139, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .empty-icon i {
        font-size: 1.75rem;
        color: #64748b;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1rem 1.25rem;
        border-top: 1px solid rgba(34, 197, 94, 0.1);
    }

    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .filter-bar {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-group {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>
@endpush

@section('content')
<div class="revenue-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="revenue-header p-4 mb-5">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.marketplace.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div class="header-icon">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-green-400 font-['Rajdhani']">Báo cáo doanh thu Marketplace</h1>
                    <p class="text-slate-500 text-xs">Thống kê và phân tích doanh thu từ cửa hàng</p>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <form method="GET" class="filter-bar mb-5">
            <div class="filter-group">
                <span class="filter-label">Từ ngày</span>
                <input type="date" name="from_date" class="filter-input" value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}">
            </div>
            <div class="filter-group">
                <span class="filter-label">Đến ngày</span>
                <input type="date" name="to_date" class="filter-input" value="{{ request('to_date', now()->format('Y-m-d')) }}">
            </div>
            <div class="filter-group">
                <span class="filter-label">Trạng thái</span>
                <select name="status" class="filter-input">
                    <option value="">Tất cả</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            <button type="submit" class="btn-filter">
                <i class="fas fa-filter mr-1"></i> Lọc
            </button>
        </form>

        <!-- Stats Grid -->
        <div class="stats-grid mb-5">
            <div class="stat-card green">
                <div class="stat-header">
                    <span class="stat-label">Tổng doanh thu</span>
                    <div class="stat-icon green"><i class="fas fa-dollar-sign"></i></div>
                </div>
                <div class="stat-value">{{ number_format($stats['total_revenue']) }}đ</div>
                @if($stats['revenue_change'] != 0)
                <div class="stat-change {{ $stats['revenue_change'] > 0 ? 'up' : 'down' }}">
                    <i class="fas fa-{{ $stats['revenue_change'] > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ abs($stats['revenue_change']) }}% so với kỳ trước
                </div>
                @endif
            </div>
            <div class="stat-card cyan">
                <div class="stat-header">
                    <span class="stat-label">Tổng đơn hàng</span>
                    <div class="stat-icon cyan"><i class="fas fa-shopping-cart"></i></div>
                </div>
                <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
                <div class="stat-change up">
                    <i class="fas fa-check-circle"></i>
                    {{ $stats['completed_orders'] }} hoàn thành
                </div>
            </div>
            <div class="stat-card amber">
                <div class="stat-header">
                    <span class="stat-label">Giá trị TB/đơn</span>
                    <div class="stat-icon amber"><i class="fas fa-receipt"></i></div>
                </div>
                <div class="stat-value">{{ number_format($stats['avg_order_value']) }}đ</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-header">
                    <span class="stat-label">Sản phẩm bán ra</span>
                    <div class="stat-icon purple"><i class="fas fa-box"></i></div>
                </div>
                <div class="stat-value">{{ number_format($stats['total_items_sold']) }}</div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
            <!-- Revenue Chart -->
            <div class="content-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-chart-area"></i>
                        <span>Biểu đồ doanh thu</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Top Products -->
            <div class="content-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-trophy"></i>
                        <span>Sản phẩm bán chạy</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="content-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-list"></i>
                    <span>Đơn hàng gần đây</span>
                </div>
                <span class="text-slate-500 text-sm">{{ $orders->total() }} đơn hàng</span>
            </div>

            @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <span class="order-code">{{ $order->order_code }}</span>
                            </td>
                            <td>
                                <div class="user-info">
                                    <img src="{{ $order->user->getDisplayAvatar() }}" class="user-avatar" alt="">
                                    <span class="user-name">{{ $order->user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-slate-400">{{ $order->items->count() }} sản phẩm</span>
                            </td>
                            <td>
                                <span class="amount">{{ number_format($order->final_amount) }}đ</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $order->payment_status }}">
                                    {{ $order->payment_status == 'paid' ? 'Đã TT' : ($order->payment_status == 'pending' ? 'Chờ TT' : 'Thất bại') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $order->status }}">
                                    {{ $order->status == 'completed' ? 'Hoàn thành' : ($order->status == 'processing' ? 'Đang xử lý' : 'Chờ xử lý') }}
                                </span>
                            </td>
                            <td>
                                <span class="text-slate-400 text-sm">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
            <div class="pagination-wrapper">
                {{ $orders->withQueryString()->links() }}
            </div>
            @endif
            @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="text-white font-semibold mb-1">Chưa có đơn hàng</h3>
                <p class="text-slate-500 text-sm">Không có đơn hàng nào trong khoảng thời gian này</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartColors = {
        green: '#22c55e',
        cyan: '#00E5FF',
        amber: '#f59e0b',
        purple: '#a855f7',
        red: '#ef4444'
    };

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Doanh thu',
                data: @json($chartData['revenue']),
                borderColor: chartColors.green,
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: chartColors.green,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(0, 229, 255, 0.05)' },
                    ticks: { color: '#64748b' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 229, 255, 0.05)' },
                    ticks: {
                        color: '#64748b',
                        callback: value => value.toLocaleString() + 'đ'
                    }
                }
            }
        }
    });

    // Top Products Chart
    const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
    new Chart(topProductsCtx, {
        type: 'bar',
        data: {
            labels: @json($topProducts->pluck('name')),
            datasets: [{
                label: 'Số lượng bán',
                data: @json($topProducts->pluck('total_sold')),
                backgroundColor: [chartColors.green, chartColors.cyan, chartColors.amber, chartColors.purple, chartColors.red],
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
});
</script>
@endpush
