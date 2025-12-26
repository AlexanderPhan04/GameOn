@extends('layouts.app')

@section('title', 'Cài đặt hệ thống')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cogs me-2"></i>Cài đặt hệ thống
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Cài đặt hệ thống</li>
            </ol>
        </nav>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- System Information -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Thông tin hệ thống
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p><strong>Tên website:</strong></p>
                            <p><strong>URL:</strong></p>
                            <p><strong>Môi trường:</strong></p>
                            <p><strong>Debug mode:</strong></p>
                        </div>
                        <div class="col-6">
                            <p>{{ $settings['site_name'] }}</p>
                            <p>{{ $settings['site_url'] }}</p>
                            <p>
                                <span class="badge bg-{{ $settings['environment'] === 'production' ? 'success' : 'warning' }}">
                                    {{ ucfirst($settings['environment']) }}
                                </span>
                            </p>
                            <p>
                                <span class="badge bg-{{ $settings['debug_mode'] ? 'danger' : 'success' }}">
                                    {{ $settings['debug_mode'] ? 'Enabled' : 'Disabled' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p><strong>Timezone:</strong></p>
                            <p><strong>Locale:</strong></p>
                            <p><strong>Cache:</strong></p>
                            <p><strong>Maintenance:</strong></p>
                        </div>
                        <div class="col-6">
                            <p>{{ $settings['timezone'] }}</p>
                            <p>{{ $settings['locale'] }}</p>
                            <p>
                                <span class="badge bg-{{ $settings['cache_enabled'] ? 'success' : 'warning' }}">
                                    {{ $settings['cache_enabled'] ? 'Enabled' : 'Disabled' }}
                                </span>
                            </p>
                            <p>
                                <span class="badge bg-{{ $settings['maintenance_mode'] ? 'danger' : 'success' }}">
                                    {{ $settings['maintenance_mode'] ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Thao tác nhanh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <button class="btn btn-outline-primary" onclick="clearCache()">
                            <i class="fas fa-broom me-2"></i>Xóa Cache hệ thống
                        </button>
                        <button class="btn btn-outline-warning" onclick="optimizeDatabase()">
                            <i class="fas fa-database me-2"></i>Tối ưu hóa Database
                        </button>
                        <button class="btn btn-outline-info" onclick="checkSystemHealth()">
                            <i class="fas fa-heartbeat me-2"></i>Kiểm tra sức khỏe hệ thống
                        </button>
                        <a href="{{ route('admin.system.logs') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-file-alt me-2"></i>Xem System Logs
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-server me-2"></i>Trạng thái hệ thống
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="text-success mb-2">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <h6>Web Server</h6>
                            <small class="text-muted">Online</small>
                        </div>
                        <div class="col-4">
                            <div class="text-success mb-2">
                                <i class="fas fa-database fa-2x"></i>
                            </div>
                            <h6>Database</h6>
                            <small class="text-muted">Connected</small>
                        </div>
                        <div class="col-4">
                            <div class="text-success mb-2">
                                <i class="fas fa-memory fa-2x"></i>
                            </div>
                            <h6>Cache</h6>
                            <small class="text-muted">Working</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function clearCache() {
        Swal.fire({
            title: 'Xóa Cache hệ thống?',
            text: 'Thao tác này sẽ xóa tất cả cache hiện tại.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("admin.system.clear-cache") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Thành công!', data.message, 'success');
                        } else {
                            Swal.fire('Lỗi!', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Lỗi!', 'Có lỗi xảy ra khi xóa cache.', 'error');
                    });
            }
        });
    }

    function optimizeDatabase() {
        Swal.fire('Thông báo', 'Chức năng tối ưu hóa database đang được phát triển.', 'info');
    }

    function checkSystemHealth() {
        Swal.fire('Thông báo', 'Chức năng kiểm tra sức khỏe hệ thống đang được phát triển.', 'info');
    }
</script>
@endsection