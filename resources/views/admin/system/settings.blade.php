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

    /* Tab Navigation Styles */
    .nav-tabs {
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }

    .nav-tabs .nav-link {
        color: #ffffff !important;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s;
        background-color: transparent !important;
    }

    .nav-tabs .nav-link * {
        color: #ffffff !important;
    }

    .nav-tabs .nav-link i,
    .nav-tabs .nav-link .fas,
    .nav-tabs .nav-link .fa,
    .nav-tabs .nav-link span {
        color: #ffffff !important;
    }

    .nav-tabs .nav-link:hover {
        color: #ffffff !important;
        border-bottom-color: rgba(255, 255, 255, 0.5);
        background-color: rgba(255, 255, 255, 0.1) !important;
    }

    .nav-tabs .nav-link:hover *,
    .nav-tabs .nav-link:hover i,
    .nav-tabs .nav-link:hover .fas,
    .nav-tabs .nav-link:hover .fa,
    .nav-tabs .nav-link:hover span {
        color: #ffffff !important;
    }

    .nav-tabs .nav-link.active {
        color: #ffffff !important;
        background-color: rgba(102, 126, 234, 0.3) !important;
        border-bottom-color: #667eea !important;
        font-weight: 600;
    }

    .nav-tabs .nav-link.active *,
    .nav-tabs .nav-link.active i,
    .nav-tabs .nav-link.active .fas,
    .nav-tabs .nav-link.active .fa,
    .nav-tabs .nav-link.active span {
        color: #ffffff !important;
        font-weight: 600 !important;
    }

    /* Theme Option Cards */
    .theme-option-card {
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid #e5e7eb;
        height: 100%;
    }

    .theme-option-card:hover {
        border-color: #667eea;
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    }

    .theme-option-card.selected {
        border-color: #667eea;
        background-color: #f0f4ff;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .theme-option-card input[type="radio"]:checked + label {
        color: #667eea;
        font-weight: 600;
    }

    /* Theme Toggle Switch Styles - 3 Levels */
    .theme-toggle-container {
        padding: 0;
    }

    .theme-toggle-wrapper-3 {
        position: relative;
        width: 240px;
        height: 38px;
    }

    .theme-toggle-switch-3 {
        position: relative;
        width: 100%;
        height: 38px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-around;
        padding: 0;
    }

    .theme-toggle-switch-3:hover {
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        transform: translateY(-2px);
    }

    .theme-toggle-slider-3 {
        position: absolute;
        width: 33.333%;
        height: 100%;
        top: 0;
        left: 0;
        background: #ffffff;
        border-radius: 50px;
        transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        z-index: 1;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .theme-slider-icon {
        position: absolute;
        font-size: 1.3rem;
        transition: opacity 0.3s ease, color 0.3s ease;
        opacity: 0;
        z-index: 10;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        color: #667eea;
        pointer-events: none;
        display: block !important;
        visibility: visible !important;
    }
    
    /* Icon trong slider - đảm bảo màu được áp dụng, override #interface * rule */
    #interface .theme-toggle-slider-3 .theme-slider-icon,
    #interface .theme-toggle-switch-3 .theme-toggle-slider-3 .theme-slider-icon {
        color: #667eea !important;
    }
    
    #interface .theme-toggle-switch-3.theme-light .theme-toggle-slider-3 .theme-slider-icon-light {
        opacity: 1 !important;
        color: #667eea !important;
    }
    
    #interface .theme-toggle-switch-3.theme-light .theme-toggle-slider-3 .theme-slider-icon-auto,
    #interface .theme-toggle-switch-3.theme-light .theme-toggle-slider-3 .theme-slider-icon-dark {
        opacity: 0 !important;
    }
    
    #interface .theme-toggle-switch-3.theme-auto .theme-toggle-slider-3 .theme-slider-icon-auto {
        opacity: 1 !important;
        color: #764ba2 !important;
    }
    
    #interface .theme-toggle-switch-3.theme-auto .theme-toggle-slider-3 .theme-slider-icon-light,
    #interface .theme-toggle-switch-3.theme-auto .theme-toggle-slider-3 .theme-slider-icon-dark {
        opacity: 0 !important;
    }
    
    #interface .theme-toggle-switch-3.theme-dark .theme-toggle-slider-3 .theme-slider-icon-dark {
        opacity: 1 !important;
        color: #4c1d95 !important;
    }
    
    #interface .theme-toggle-switch-3.theme-dark .theme-toggle-slider-3 .theme-slider-icon-light,
    #interface .theme-toggle-switch-3.theme-dark .theme-toggle-slider-3 .theme-slider-icon-auto {
        opacity: 0 !important;
    }
    
    .theme-toggle-switch-3.theme-dark .theme-slider-icon-light,
    .theme-toggle-switch-3.theme-dark .theme-slider-icon-auto {
        opacity: 0 !important;
    }

    .theme-toggle-switch-3.theme-light .theme-toggle-slider-3 {
        left: 0;
    }

    .theme-toggle-switch-3.theme-auto .theme-toggle-slider-3 {
        left: 33.333%;
    }

    .theme-toggle-switch-3.theme-dark .theme-toggle-slider-3 {
        left: 66.666%;
    }

    .theme-icon-light,
    .theme-icon-auto,
    .theme-icon-dark {
        position: relative;
        font-size: 1rem;
        transition: all 0.3s ease;
        z-index: 2;
        flex: 1;
        text-align: center;
        pointer-events: none;
    }

    .theme-icon-light {
        color: #ffd700;
    }

    .theme-icon-auto {
        color: #9ca3af;
    }

    .theme-icon-dark {
        color: #87ceeb;
    }
    
    /* Change icon color when slider is over them */
    .theme-toggle-switch-3.theme-light .theme-icon-light {
        color: #667eea;
    }
    
    .theme-toggle-switch-3.theme-auto .theme-icon-auto {
        color: #667eea;
    }
    
    .theme-toggle-switch-3.theme-dark .theme-icon-dark {
        color: #667eea;
    }

    .theme-labels-3 {
        position: absolute;
        bottom: -18px;
        width: 100%;
        display: flex;
        justify-content: space-between;
        padding: 0 4px;
    }

    .theme-label-light,
    .theme-label-auto,
    .theme-label-dark {
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.7rem;
        transition: all 0.3s ease;
        flex: 1;
        text-align: center;
    }

    .theme-label-light {
        text-align: left;
    }

    .theme-label-auto {
        text-align: center;
    }

    .theme-label-dark {
        text-align: right;
    }

    .theme-label-left {
        opacity: 1;
    }

    .theme-label-right {
        opacity: 0.5;
    }

    .theme-toggle-switch.theme-dark ~ .theme-labels .theme-label-left {
        opacity: 0.5;
    }

    .theme-toggle-switch.theme-dark ~ .theme-labels .theme-label-right {
        opacity: 1;
    }

    .theme-radio {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    /* Đảm bảo màu chữ trắng trong tab Interface Settings - Override tất cả */
    #interface {
        color: #ffffff !important;
    }

    #interface * {
        color: #ffffff !important;
    }

    #interface .card-header {
        color: #ffffff !important;
        background-color: rgba(102, 126, 234, 0.1) !important;
    }

    #interface .card-header h6 {
        color: #ffffff !important;
        font-weight: 700 !important;
    }

    #interface .card-header i,
    #interface .card-header .fas,
    #interface .card-header .fa {
        color: #ffffff !important;
    }

    #interface .card-body {
        color: #ffffff !important;
        background-color: rgba(0, 0, 0, 0.3) !important;
    }

    #interface .card-body h6 {
        color: #ffffff !important;
        font-weight: 600 !important;
    }

    #interface .card-body p {
        color: #ffffff !important;
    }

    #interface .card-body p.text-muted {
        color: #e0e0e0 !important;
    }

    #interface .card-body p strong {
        color: #ffffff !important;
        font-weight: 700 !important;
    }

    #interface .card-body label {
        color: #ffffff !important;
    }

    #interface .card-body .form-label {
        color: #ffffff !important;
        font-weight: 700 !important;
    }

    #interface .card-body .form-label i,
    #interface .card-body .form-label .fas,
    #interface .card-body .form-label .fa {
        color: #ffffff !important;
    }

    #interface .card-body .form-check-label {
        color: #ffffff !important;
    }

    /* Theme option cards */
    #interface .theme-option-card {
        color: #ffffff !important;
        background-color: rgba(0, 0, 0, 0.2) !important;
    }

    #interface .theme-option-card .card-body {
        color: #ffffff !important;
    }

    #interface .theme-option-card .card-body h6 {
        color: #ffffff !important;
        font-weight: 600 !important;
    }

    #interface .theme-option-card .card-body p {
        color: #ffffff !important;
    }

    #interface .theme-option-card .card-body p.text-muted {
        color: #e0e0e0 !important;
    }

    #interface .theme-option-card .card-body label {
        color: #ffffff !important;
    }

    #interface .theme-option-card .card-body .form-check-label {
        color: #ffffff !important;
    }

    /* Icons trong theme cards - giữ màu gốc nhưng sáng hơn */
    #interface .theme-option-card .card-body .text-warning,
    #interface .theme-option-card .card-body .text-warning i {
        color: #ffd700 !important;
    }

    #interface .theme-option-card .card-body .text-info,
    #interface .theme-option-card .card-body .text-info i {
        color: #87ceeb !important;
    }

    #interface .theme-option-card .card-body .text-secondary,
    #interface .theme-option-card .card-body .text-secondary i {
        color: #c0c0c0 !important;
    }

    /* Card thông tin bên phải */
    #interface .col-lg-4 .card-body {
        color: #ffffff !important;
        background-color: rgba(0, 0, 0, 0.3) !important;
    }

    #interface .col-lg-4 .card-body p {
        color: #ffffff !important;
    }

    #interface .col-lg-4 .card-body p.text-muted {
        color: #e0e0e0 !important;
    }

    #interface .col-lg-4 .card-body p strong {
        color: #ffffff !important;
        font-weight: 700 !important;
    }

    #interface .col-lg-4 .card-body i.text-warning {
        color: #ffd700 !important;
    }

    #interface .col-lg-4 .card-body i.text-info {
        color: #87ceeb !important;
    }

    #interface .col-lg-4 .card-body i.text-secondary {
        color: #c0c0c0 !important;
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

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button" role="tab" aria-controls="system" aria-selected="true" style="color: #ffffff !important;">
                <i class="fas fa-cogs me-2" style="color: #ffffff !important;"></i><span style="color: #ffffff !important; font-weight: 600;">Hệ thống</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="interface-tab" data-bs-toggle="tab" data-bs-target="#interface" type="button" role="tab" aria-controls="interface" aria-selected="false" style="color: #ffffff !important;">
                <i class="fas fa-palette me-2" style="color: #ffffff !important;"></i><span style="color: #ffffff !important; font-weight: 600;">Cài đặt giao diện</span>
            </button>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content" id="settingsTabsContent">
        <!-- System Settings Tab -->
        <div class="tab-pane fade show active" id="system" role="tabpanel" aria-labelledby="system-tab">
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

        <!-- Interface Settings Tab -->
        <div class="tab-pane fade" id="interface" role="tabpanel" aria-labelledby="interface-tab">
            <div class="row">
                <div class="col-12">
                    <!-- Toggle Switch Container - 3 Levels -->
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <label class="form-label fw-bold mb-0" style="color: #ffffff !important; font-size: 0.95rem; white-space: nowrap; line-height: 38px; height: 38px; display: flex; align-items: center;">
                            <i class="fas fa-moon me-2" style="color: #ffffff !important;"></i>Chế độ hiển thị
                        </label>
                        
                        <div class="theme-toggle-container" style="flex: 1; display: flex; align-items: center;">
                            <div class="theme-toggle-wrapper-3" style="margin: 0;">
                                    <input type="radio" name="theme" id="theme-light" value="light" {{ session('theme', 'light') === 'light' ? 'checked' : '' }} class="theme-radio" onchange="updateToggleSwitch3(); saveTheme()">
                                    <input type="radio" name="theme" id="theme-auto" value="auto" {{ session('theme', 'light') === 'auto' ? 'checked' : '' }} class="theme-radio" onchange="updateToggleSwitch3(); saveTheme()">
                                    <input type="radio" name="theme" id="theme-dark" value="dark" {{ session('theme', 'light') === 'dark' ? 'checked' : '' }} class="theme-radio" onchange="updateToggleSwitch3(); saveTheme()">
                                    
                                    <div class="theme-toggle-switch-3 {{ session('theme', 'light') === 'auto' ? 'theme-auto' : (session('theme', 'light') === 'dark' ? 'theme-dark' : 'theme-light') }}" onclick="clickTheme(event)">
                                        <!-- Icons on the track background -->
                                        <i class="fas fa-sun theme-icon-light"></i>
                                        <i class="fas fa-adjust theme-icon-auto"></i>
                                        <i class="fas fa-moon theme-icon-dark"></i>
                                        
                                        <!-- Slider with icon -->
                                        <div class="theme-toggle-slider-3">
                                            <i class="fas fa-sun theme-slider-icon theme-slider-icon-light"></i>
                                            <i class="fas fa-adjust theme-slider-icon theme-slider-icon-auto"></i>
                                            <i class="fas fa-moon theme-slider-icon theme-slider-icon-dark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Initialize theme selection on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateToggleSwitch3();
        
        // Check URL hash and activate corresponding tab
        if (window.location.hash === '#interface') {
            const interfaceTab = document.getElementById('interface-tab');
            const interfacePane = document.getElementById('interface');
            const systemTab = document.getElementById('system-tab');
            const systemPane = document.getElementById('system');
            
            if (interfaceTab && interfacePane) {
                // Remove active class from system tab
                if (systemTab) {
                    systemTab.classList.remove('active');
                    systemTab.setAttribute('aria-selected', 'false');
                }
                if (systemPane) {
                    systemPane.classList.remove('show', 'active');
                }
                
                // Add active class to interface tab
                interfaceTab.classList.add('active');
                interfaceTab.setAttribute('aria-selected', 'true');
                interfacePane.classList.add('show', 'active');
            }
        }
    });

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

    // Click on toggle switch to jump directly to clicked position
    function clickTheme(event) {
        const toggleSwitch = event.currentTarget;
        const rect = toggleSwitch.getBoundingClientRect();
        const clickX = event.clientX - rect.left;
        const width = rect.width;
        
        const lightRadio = document.getElementById('theme-light');
        const autoRadio = document.getElementById('theme-auto');
        const darkRadio = document.getElementById('theme-dark');
        
        // Calculate which third was clicked (left, middle, or right)
        const third = width / 3;
        
        if (clickX < third) {
            // Clicked on left third - Light mode
            lightRadio.checked = true;
            autoRadio.checked = false;
            darkRadio.checked = false;
        } else if (clickX < third * 2) {
            // Clicked on middle third - Auto mode
            autoRadio.checked = true;
            lightRadio.checked = false;
            darkRadio.checked = false;
        } else {
            // Clicked on right third - Dark mode
            darkRadio.checked = true;
            lightRadio.checked = false;
            autoRadio.checked = false;
        }
        
        updateToggleSwitch3();
        saveTheme();
    }

    // Update toggle switch visual state for 3 levels
    function updateToggleSwitch3() {
        const lightRadio = document.getElementById('theme-light');
        const autoRadio = document.getElementById('theme-auto');
        const darkRadio = document.getElementById('theme-dark');
        const toggleSwitch = document.querySelector('.theme-toggle-switch-3');
        
        // Remove all classes
        toggleSwitch.classList.remove('theme-light', 'theme-auto', 'theme-dark');
        
        if (lightRadio.checked) {
            toggleSwitch.classList.add('theme-light');
        } else if (autoRadio.checked) {
            toggleSwitch.classList.add('theme-auto');
        } else if (darkRadio.checked) {
            toggleSwitch.classList.add('theme-dark');
        }
    }

    // Auto save theme
    function saveTheme() {
        const lightRadio = document.getElementById('theme-light');
        const autoRadio = document.getElementById('theme-auto');
        const darkRadio = document.getElementById('theme-dark');
        
        let theme = 'light';
        if (autoRadio.checked) {
            theme = 'auto';
        } else if (darkRadio.checked) {
            theme = 'dark';
        }

        fetch('{{ route("admin.system.update-theme") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ theme: theme })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to apply theme, but keep interface tab active
                window.location.hash = '#interface';
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error saving theme:', error);
        });
    }
</script>
@endsection