@extends('layouts.app')

@section('title', 'System Logs')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-alt me-2"></i>System Logs
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">System Logs</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- System Information -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-server me-2"></i>Thông tin hệ thống
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <p><strong>PHP Version:</strong> {{ $systemInfo['php_version'] }}</p>
                            <p><strong>Laravel Version:</strong> {{ $systemInfo['laravel_version'] }}</p>
                            <p><strong>Server:</strong> {{ $systemInfo['server_software'] }}</p>
                            <p><strong>Memory Limit:</strong> {{ $systemInfo['memory_limit'] }}</p>
                            <p><strong>Max Execution Time:</strong> {{ $systemInfo['max_execution_time'] }}s</p>
                            <p><strong>Database Size:</strong> {{ $systemInfo['database_size'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disk Space -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-hdd me-2"></i>Dung lượng ổ đĩa
                    </h6>
                </div>
                <div class="card-body">
                    <p><strong>Tổng dung lượng:</strong> {{ $systemInfo['disk_space']['total'] }}</p>
                    <p><strong>Đã sử dụng:</strong> {{ $systemInfo['disk_space']['used'] }}</p>
                    <p><strong>Còn trống:</strong> {{ $systemInfo['disk_space']['free'] }}</p>

                    <div class="progress mt-3">
                        @php
                        $percentage = $systemInfo['disk_space']['used_percentage'];
                        $colorClass = $percentage > 80 ? 'danger' : ($percentage > 60 ? 'warning' : 'success');
                        @endphp
                        <div class="progress-bar bg-{{ $colorClass }}"
                            role="progressbar"
                            data-percentage="{{ $percentage }}">
                            {{ $percentage }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Log Viewer -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>Log gần đây (100 dòng cuối)
                    </h6>
                    <button class="btn btn-sm btn-outline-secondary" onclick="refreshLogs()">
                        <i class="fas fa-sync-alt me-1"></i>Làm mới
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="log-container" style="height: 600px; overflow-y: auto; background-color: #1e1e1e; color: #d4d4d4; font-family: 'Courier New', monospace; font-size: 12px;">
                        @if(count($logs) > 0)
                        @foreach($logs as $log)
                        @if(!empty(trim($log)))
                        <div class="log-line p-2 border-bottom" style="border-color: #333 !important; white-space: pre-wrap; word-wrap: break-word;">{{ $log }}</div>
                        @endif
                        @endforeach
                        @else
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-file-alt fa-3x mb-3"></i>
                            <p>Không có log nào được tìm thấy</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function refreshLogs() {
        location.reload();
    }

    // Auto-scroll to bottom of logs and set progress bar width
    document.addEventListener('DOMContentLoaded', function() {
        const logContainer = document.querySelector('.log-container');
        if (logContainer) {
            logContainer.scrollTop = logContainer.scrollHeight;
        }

        // Set progress bar width from data attribute
        const progressBar = document.querySelector('.progress-bar[data-percentage]');
        if (progressBar) {
            const percentage = progressBar.getAttribute('data-percentage');
            progressBar.style.width = percentage + '%';
        }
    });
</script>

<style>
    .log-line:hover {
        background-color: #2d2d30 !important;
    }

    .log-line:nth-child(even) {
        background-color: #252526;
    }

    /* Highlight error lines */
    .log-line:contains('ERROR') {
        background-color: rgba(220, 53, 69, 0.1) !important;
        border-left: 3px solid #dc3545;
    }

    /* Highlight warning lines */
    .log-line:contains('WARNING') {
        background-color: rgba(255, 193, 7, 0.1) !important;
        border-left: 3px solid #ffc107;
    }

    /* Highlight info lines */
    .log-line:contains('INFO') {
        background-color: rgba(13, 202, 240, 0.1) !important;
        border-left: 3px solid #0dcaf0;
    }
</style>
@endsection