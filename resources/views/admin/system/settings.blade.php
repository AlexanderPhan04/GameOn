@extends('layouts.app')

@section('title', 'Cài đặt hệ thống')

@push('styles')
<style>
    /* Header section với màu hệ thống (gradient xanh/tím) */
    .settings-header {
        background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 30%, #16213e 70%, #0f0f23 100%);
        padding: 2rem 0;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid rgba(102, 126, 234, 0.2);
        position: relative;
        z-index: 1;
        width: 100%;
    }
    
    /* Đảm bảo container-fluid trong header có padding đúng */
    .settings-header .container-fluid {
        padding-left: 20px;
        padding-right: 20px;
    }
    
    /* Khi có sidebar, điều chỉnh để full width */
    body.has-admin-sidebar .settings-header {
        margin-left: -280px;
        padding-left: 280px;
        width: calc(100% + 280px);
    }
    
    @media (max-width: 991.98px) {
        body.has-admin-sidebar .settings-header {
            margin-left: 0;
            padding-left: 0;
            width: 100%;
        }
    }
    
    /* Đảm bảo màu chữ rõ ràng cho toàn bộ trang settings */
    .container-fluid {
        color: #212529 !important;
        background-color: transparent !important;
    }
    
    /* Tiêu đề chính - Màu trắng trên nền hệ thống - Override tất cả */
    .settings-header h1,
    .settings-header .h3,
    .settings-header h1.h3,
    .settings-header h1.text-gray-800,
    .settings-header .container-fluid h1,
    .settings-header .container-fluid .h3,
    .settings-header .container-fluid h1.h3 {
        color: #ffffff !important;
        font-weight: 700 !important;
        margin: 0 !important;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5) !important;
    }
    
    .settings-header h1 *,
    .settings-header h1 i,
    .settings-header .h3 i,
    .settings-header h1.h3 i,
    .settings-header .container-fluid h1 i,
    .settings-header .container-fluid h1 .fas,
    .settings-header .container-fluid h1 .fa {
        color: #ffffff !important;
        margin-right: 0.75rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5) !important;
    }
    
    /* Override bất kỳ màu nào từ layout hoặc Bootstrap */
    .settings-header h1.text-gray-800 {
        color: #ffffff !important;
    }
    
    /* Đảm bảo tất cả text trong settings-header là trắng */
    .settings-header {
        color: #ffffff !important;
    }
    
    /* Các tiêu đề khác vẫn giữ màu đen */
    .container-fluid h1,
    .container-fluid h1.h3,
    .container-fluid h1.text-gray-800,
    h1.h3.text-gray-800,
    .container-fluid .h3,
    .content-wrapper h1,
    .content-wrapper h1.h3,
    .content-wrapper h1.text-gray-800,
    .content-wrapper-with-sidebar h1,
    .content-wrapper-with-sidebar h1.h3,
    .content-wrapper-with-sidebar h1.text-gray-800 {
        color: #1a202c !important;
        font-weight: 700 !important;
        margin: 0 !important;
    }
    
    /* Override bất kỳ màu nào từ layout */
    .content-wrapper .container-fluid h1,
    .content-wrapper-with-sidebar .container-fluid h1 {
        color: #1a202c !important;
    }
    
    .container-fluid h1 i {
        color: #667eea !important;
        margin-right: 0.75rem;
    }
    
    /* Breadcrumb đẹp hơn */
    .settings-header .breadcrumb {
        margin: 0;
        padding: 0;
        background: transparent;
    }
    
    .settings-header .breadcrumb-item a {
        color: #667eea !important;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }
    
    .settings-header .breadcrumb-item a:hover {
        color: #5568d3 !important;
    }
    
    .settings-header .breadcrumb-item.active {
        color: #6b7280 !important;
        font-weight: 500;
    }
    
    .settings-header .breadcrumb-item + .breadcrumb-item::before {
        content: "/";
        color: #9ca3af;
        padding: 0 0.5rem;
    }
    
    /* Card body và tất cả phần tử bên trong */
    .card-body {
        color: #212529 !important;
    }
    
    .card-body * {
        color: inherit;
    }
    
    .card-body p {
        color: #212529 !important;
        margin-bottom: 0.75rem;
    }
    
    /* Đảm bảo các label (strong) có màu chữ rõ ràng */
    .card-body p strong {
        color: #1a202c !important;
        font-weight: 700 !important;
    }
    
    .card-body strong {
        color: #1a202c !important;
        font-weight: 700 !important;
    }
    
    .card-body h6 {
        color: #212529 !important;
    }
    
    .card-body small {
        color: #6c757d !important;
    }
    
    .card-body .text-muted {
        color: #6c757d !important;
    }
    
    /* Đảm bảo breadcrumb có màu chữ rõ ràng */
    .breadcrumb {
        background-color: transparent !important;
    }
    
    .breadcrumb-item {
        color: #4a5568 !important;
    }
    
    .breadcrumb-item a {
        color: #667eea !important;
        text-decoration: none;
        font-weight: 500 !important;
    }
    
    .breadcrumb-item.active {
        color: #4a5568 !important;
        font-weight: 600 !important;
    }
    
    /* Đảm bảo card header có màu chữ rõ ràng */
    .card-header h6.text-primary {
        color: #667eea !important;
    }
    
    .card-header h6 {
        color: #667eea !important;
        font-weight: 700 !important;
    }
    
    /* Đảm bảo các badge có màu chữ rõ ràng */
    .badge {
        color: #fff !important;
    }
    
    /* Đảm bảo các nút có màu chữ rõ ràng */
    .btn-outline-primary,
    .btn-outline-warning,
    .btn-outline-info,
    .btn-outline-secondary {
        color: inherit !important;
    }
    
    /* Đảm bảo icon trong card body có màu phù hợp */
    .card-body .text-success {
        color: #22c55e !important;
    }
    
    /* Đảm bảo tất cả text trong container-fluid có màu rõ ràng */
    .container-fluid,
    .content-wrapper .container-fluid,
    .content-wrapper-with-sidebar .container-fluid {
        color: #212529 !important;
    }
    
    /* Override bất kỳ màu trắng nào có thể được áp dụng từ layout */
    .container-fluid *:not(i):not(.badge):not(.btn) {
        color: #212529 !important;
    }
    
    /* Đảm bảo các phần tử cụ thể có màu chữ rõ ràng */
    .container-fluid p,
    .container-fluid strong,
    .container-fluid span:not(.badge),
    .container-fluid div:not(.badge):not(.btn) {
        color: #212529 !important;
    }
    
    /* Đặc biệt cho các label trong card */
    .card-body .col-6:first-child p strong,
    .card-body .col-6:first-child strong {
        color: #1a202c !important;
        font-weight: 700 !important;
    }
</style>
@endpush

@section('content')
<!-- Header Section với màu hệ thống -->
<div class="settings-header">
    <div class="container-fluid">
        <h1 class="h3 mb-0" style="color: #ffffff !important;">
            <i class="fas fa-cogs" style="color: #ffffff !important;"></i>Cài đặt hệ thống
        </h1>
    </div>
</div>

<!-- Main Content -->
<div class="container-fluid">

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