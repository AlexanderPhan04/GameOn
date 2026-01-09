@extends('layouts.app')

@section('title', 'Báo cáo Analytics')

@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    .chart-container canvas {
        max-height: 300px;
    }
    
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.25rem 2rem 0 rgba(58, 59, 69, 0.25) !important;
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
    }
    
    .card-header h6 {
        color: white !important;
        font-weight: 600;
    }
    
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    
    .text-primary {
        color: #4e73df !important;
    }
    
    .text-success {
        color: #1cc88a !important;
    }
    
    .text-info {
        color: #36b9cc !important;
    }
    
    .text-warning {
        color: #f6c23e !important;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.5rem 0.75rem;
    }
    
    .table td {
        padding: 0.75rem 0;
        border: none;
        vertical-align: middle;
    }
    
    .table td i {
        width: 20px;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-bar me-2"></i>Báo cáo Analytics
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Analytics</li>
            </ol>
        </nav>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Users Statistics -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng số người dùng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['users']['total']) }}</div>
                            <div class="text-xs text-success mt-1">
                                <i class="fas fa-arrow-up"></i> {{ $stats['users']['new_this_month'] }} mới trong tháng
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teams Statistics -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tổng số đội
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['teams']['total']) }}</div>
                            <div class="text-xs text-success mt-1">
                                <i class="fas fa-arrow-up"></i> {{ $stats['teams']['new_this_month'] }} mới trong tháng
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tournaments Statistics -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Giải đấu
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['tournaments']['total']) }}</div>
                            <div class="text-xs text-info mt-1">
                                {{ $stats['tournaments']['active'] }} đang diễn ra
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-trophy fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Games Statistics -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Game có sẵn
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['games']['total']) }}</div>
                            <div class="text-xs text-warning mt-1">
                                {{ $stats['games']['active'] }} game đang hoạt động
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gamepad fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Registration Trend -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>Xu hướng đăng ký người dùng (12 tháng qua)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="userRegistrationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Status Breakdown -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Trạng thái người dùng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="userStatusChart"></canvas>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-circle text-success me-1"></i>Hoạt động</span>
                            <span class="font-weight-bold">{{ $stats['users']['active'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-circle text-warning me-1"></i>Tạm khóa</span>
                            <span class="font-weight-bold">{{ $stats['users']['suspended'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-circle text-danger me-1"></i>Bị cấm</span>
                            <span class="font-weight-bold">{{ $stats['users']['banned'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Charts Row -->
    <div class="row">
        <!-- Monthly Activity Chart -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Hoạt động theo tháng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyActivityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team vs Tournament Chart -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Đội vs Giải đấu
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="teamTournamentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users-cog me-2"></i>Thống kê chi tiết người dùng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-check-circle text-success me-2"></i>Tài khoản hoạt động</td>
                                    <td class="text-end"><span class="badge bg-success">{{ $stats['users']['active'] }}</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-pause-circle text-warning me-2"></i>Tài khoản tạm khóa</td>
                                    <td class="text-end"><span class="badge bg-warning">{{ $stats['users']['suspended'] }}</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-ban text-danger me-2"></i>Tài khoản bị cấm</td>
                                    <td class="text-end"><span class="badge bg-danger">{{ $stats['users']['banned'] }}</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-user-plus text-info me-2"></i>Đăng ký tháng này</td>
                                    <td class="text-end"><span class="badge bg-info">{{ $stats['users']['new_this_month'] }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-trophy me-2"></i>Thống kê giải đấu
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-play-circle text-success me-2"></i>Giải đấu đang diễn ra</td>
                                    <td class="text-end"><span class="badge bg-success">{{ $stats['tournaments']['active'] }}</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-flag-checkered text-info me-2"></i>Giải đấu đã kết thúc</td>
                                    <td class="text-end"><span class="badge bg-info">{{ $stats['tournaments']['completed'] }}</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-plus-circle text-warning me-2"></i>Tạo mới tháng này</td>
                                    <td class="text-end"><span class="badge bg-warning">{{ $stats['tournaments']['new_this_month'] }}</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-list text-secondary me-2"></i>Tổng số giải đấu</td>
                                    <td class="text-end"><span class="badge bg-secondary">{{ $stats['tournaments']['total'] }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Data from PHP -->
<script id="chartData" type="application/json">
    {
        "userTrend": {!! json_encode($userTrend) !!},
        "userStats": {!! json_encode($stats['users']) !!}
    }
</script>

<script>
    // Get data from embedded JSON
    const chartDataElement = document.getElementById('chartData');
    const chartData = JSON.parse(chartDataElement.textContent);
    const userTrendData = chartData.userTrend;
    const userStatsData = chartData.userStats;

    // User Registration Trend Chart
    const userTrendCtx = document.getElementById('userRegistrationChart').getContext('2d');
    const userRegistrationChart = new Chart(userTrendCtx, {
        type: 'line',
        data: {
            labels: userTrendData.map(item => item.month),
            datasets: [{
                label: 'Số lượng đăng ký',
                data: userTrendData.map(item => item.count),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#667eea',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 12
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // User Status Pie Chart
    const statusCtx = document.getElementById('userStatusChart').getContext('2d');
    const userStatusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Hoạt động', 'Tạm khóa', 'Bị cấm'],
            datasets: [{
                data: [userStatsData.active, userStatsData.suspended, userStatsData.banned],
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#dc3545'
                ],
                hoverBackgroundColor: [
                    '#218838',
                    '#e0a800',
                    '#c82333'
                ],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#667eea',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%',
            radius: '90%'
        }
    });

    // Monthly Activity Bar Chart
    const monthlyActivityCtx = document.getElementById('monthlyActivityChart').getContext('2d');
    const monthlyActivityChart = new Chart(monthlyActivityCtx, {
        type: 'bar',
        data: {
            labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            datasets: [{
                label: 'Người dùng mới',
                data: [2, 1, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0], // Dữ liệu mẫu
                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                borderColor: '#667eea',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }, {
                label: 'Đội mới',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], // Dữ liệu mẫu
                backgroundColor: 'rgba(40, 167, 69, 0.8)',
                borderColor: '#28a745',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#667eea',
                    borderWidth: 1,
                    cornerRadius: 8
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });

    // Team vs Tournament Pie Chart
    const teamTournamentCtx = document.getElementById('teamTournamentChart').getContext('2d');
    const teamTournamentChart = new Chart(teamTournamentCtx, {
        type: 'pie',
        data: {
            labels: ['Đội', 'Giải đấu'],
            datasets: [{
                data: [
                    {{ $stats['teams']['total'] ?? 0 }}, 
                    {{ $stats['tournaments']['total'] ?? 0 }}
                ],
                backgroundColor: [
                    '#28a745',
                    '#ffc107'
                ],
                hoverBackgroundColor: [
                    '#218838',
                    '#e0a800'
                ],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#667eea',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
</script>
@endsection