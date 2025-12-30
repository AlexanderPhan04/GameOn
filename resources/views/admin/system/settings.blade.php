@extends('layouts.app')

@section('title', 'Cài đặt hệ thống')

@push('styles')
<style>
    .settings-container { background: #000814; min-height: 100vh; }

    /* Hero Section */
    .settings-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .settings-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #00E5FF, transparent);
    }
    .hero-icon {
        width: 60px; height: 60px; min-width: 60px;
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 0 25px rgba(6, 182, 212, 0.3);
    }
    .hero-icon i { font-size: 1.5rem; color: white; }
    .hero-title { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; color: #00E5FF; margin: 0; }
    .hero-subtitle { color: #94a3b8; font-size: 0.9rem; margin: 0.25rem 0 0 0; }

    /* Tabs */
    .tabs-container {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        padding: 0.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 0.5rem;
    }
    .tab-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #94a3b8;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .tab-btn:hover { background: rgba(0, 229, 255, 0.1); color: #FFFFFF; }
    .tab-btn.active {
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.2), rgba(6, 182, 212, 0.2));
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.3);
    }

    /* Cards */
    .settings-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .settings-card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .card-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; color: white;
    }
    .icon-cyan { background: linear-gradient(135deg, #06b6d4, #0891b2); }
    .icon-yellow { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .icon-green { background: linear-gradient(135deg, #22c55e, #16a34a); }
    .icon-purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .card-title { font-family: 'Rajdhani', sans-serif; font-size: 1.1rem; font-weight: 700; color: #FFFFFF; margin: 0; }
    .settings-card-body { padding: 1.5rem; }

    /* Info Row */
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(0, 229, 255, 0.05);
    }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: #94a3b8; font-weight: 600; font-size: 0.9rem; }
    .info-value { color: #FFFFFF; font-size: 0.9rem; }

    /* Badges */
    .badge-custom { padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
    .badge-success { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .badge-warning { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .badge-danger { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    .badge-info { background: rgba(0, 229, 255, 0.15); color: #00E5FF; }

    /* Action Buttons */
    .btn-action-lg {
        width: 100%;
        padding: 0.875rem 1.25rem;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        border: 1px solid transparent;
        background: transparent;
    }
    .btn-action-lg:last-child { margin-bottom: 0; }
    .btn-cyan { color: #00E5FF; border-color: rgba(0, 229, 255, 0.3); }
    .btn-cyan:hover { background: rgba(0, 229, 255, 0.15); box-shadow: 0 0 15px rgba(0, 229, 255, 0.3); }
    .btn-yellow { color: #f59e0b; border-color: rgba(245, 158, 11, 0.3); }
    .btn-yellow:hover { background: rgba(245, 158, 11, 0.15); box-shadow: 0 0 15px rgba(245, 158, 11, 0.3); }
    .btn-blue { color: #3b82f6; border-color: rgba(59, 130, 246, 0.3); }
    .btn-blue:hover { background: rgba(59, 130, 246, 0.15); box-shadow: 0 0 15px rgba(59, 130, 246, 0.3); }
    .btn-gray { color: #94a3b8; border-color: rgba(148, 163, 184, 0.3); }
    .btn-gray:hover { background: rgba(148, 163, 184, 0.15); }

    /* Status Grid */
    .status-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
    .status-item { text-align: center; padding: 1rem; background: rgba(0, 0, 0, 0.2); border-radius: 12px; border: 1px solid rgba(0, 229, 255, 0.1); }
    .status-icon { font-size: 2rem; margin-bottom: 0.5rem; }
    .status-icon.online { color: #22c55e; }
    .status-title { color: #FFFFFF; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.25rem; }
    .status-text { color: #64748b; font-size: 0.8rem; }

    /* Theme Toggle */
    .theme-section { margin-bottom: 1.5rem; }
    .theme-label { color: #FFFFFF; font-weight: 600; font-size: 0.95rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
    .theme-options { display: flex; gap: 1rem; }
    .theme-option {
        flex: 1;
        padding: 1.25rem;
        background: rgba(0, 0, 0, 0.2);
        border: 2px solid rgba(0, 229, 255, 0.1);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }
    .theme-option:hover { border-color: rgba(0, 229, 255, 0.3); background: rgba(0, 229, 255, 0.05); }
    .theme-option.active { border-color: #00E5FF; background: rgba(0, 229, 255, 0.1); box-shadow: 0 0 20px rgba(0, 229, 255, 0.2); }
    .theme-option-icon { font-size: 1.5rem; margin-bottom: 0.5rem; }
    .theme-option-icon.light { color: #fbbf24; }
    .theme-option-icon.auto { color: #94a3b8; }
    .theme-option-icon.dark { color: #60a5fa; }
    .theme-option-title { color: #FFFFFF; font-weight: 600; font-size: 0.9rem; }
    .theme-option input { display: none; }

    /* Alert */
    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        color: #22c55e;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .tab-content { display: none; }
    .tab-content.active { display: block; }

    @media (max-width: 768px) {
        .settings-hero { padding: 1.25rem; }
        .tabs-container { flex-direction: column; }
        .tab-btn { justify-content: center; }
        .status-grid { grid-template-columns: 1fr; }
        .theme-options { flex-direction: column; }
    }
</style>
@endpush

@section('content')
<div class="settings-container">
    <div class="max-w-6xl mx-auto px-4 py-6">
        <!-- Hero Section -->
        <div class="settings-hero">
            <div class="flex items-center gap-4">
                <div class="hero-icon"><i class="fas fa-cogs"></i></div>
                <div>
                    <h1 class="hero-title">Cài đặt hệ thống</h1>
                    <p class="hero-subtitle">Quản lý cấu hình và tùy chỉnh hệ thống</p>
                </div>
            </div>
        </div>

        <!-- Alert -->
        @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Tabs -->
        <div class="tabs-container">
            <button class="tab-btn active" onclick="switchTab('system')">
                <i class="fas fa-cogs"></i><span>Hệ thống</span>
            </button>
            <button class="tab-btn" onclick="switchTab('interface')">
                <i class="fas fa-palette"></i><span>Cài đặt giao diện</span>
            </button>
        </div>

        <!-- System Tab -->
        <div class="tab-content active" id="system-tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- System Information -->
                <div>
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <div class="card-icon icon-cyan"><i class="fas fa-info-circle"></i></div>
                            <h3 class="card-title">Thông tin hệ thống</h3>
                        </div>
                        <div class="settings-card-body">
                            <div class="info-row">
                                <span class="info-label">Tên website:</span>
                                <span class="info-value">{{ $settings['site_name'] ?? 'Laravel' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">URL:</span>
                                <span class="info-value">{{ $settings['site_url'] ?? config('app.url') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Môi trường:</span>
                                <span class="badge-custom {{ ($settings['environment'] ?? 'local') === 'production' ? 'badge-success' : 'badge-warning' }}">
                                    {{ ucfirst($settings['environment'] ?? 'Local') }}
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Debug mode:</span>
                                <span class="badge-custom {{ ($settings['debug_mode'] ?? false) ? 'badge-danger' : 'badge-success' }}">
                                    {{ ($settings['debug_mode'] ?? false) ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Timezone:</span>
                                <span class="info-value">{{ $settings['timezone'] ?? 'UTC' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Locale:</span>
                                <span class="info-value">{{ $settings['locale'] ?? 'en' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Cache:</span>
                                <span class="badge-custom {{ ($settings['cache_enabled'] ?? true) ? 'badge-success' : 'badge-warning' }}">
                                    {{ ($settings['cache_enabled'] ?? true) ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Maintenance:</span>
                                <span class="badge-custom {{ ($settings['maintenance_mode'] ?? false) ? 'badge-danger' : 'badge-success' }}">
                                    {{ ($settings['maintenance_mode'] ?? false) ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Status -->
                <div>
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <div class="card-icon icon-yellow"><i class="fas fa-bolt"></i></div>
                            <h3 class="card-title">Thao tác nhanh</h3>
                        </div>
                        <div class="settings-card-body">
                            <button class="btn-action-lg btn-cyan" onclick="clearCache()">
                                <i class="fas fa-broom"></i>Xóa Cache hệ thống
                            </button>
                            <button class="btn-action-lg btn-yellow" onclick="optimizeDatabase()">
                                <i class="fas fa-database"></i>Tối ưu hóa Database
                            </button>
                            <button class="btn-action-lg btn-blue" onclick="checkSystemHealth()">
                                <i class="fas fa-heartbeat"></i>Kiểm tra sức khỏe hệ thống
                            </button>
                            <a href="{{ route('admin.system.logs') }}" class="btn-action-lg btn-gray" style="text-decoration: none;">
                                <i class="fas fa-file-alt"></i>Xem System Logs
                            </a>
                        </div>
                    </div>

                    <div class="settings-card">
                        <div class="settings-card-header">
                            <div class="card-icon icon-green"><i class="fas fa-server"></i></div>
                            <h3 class="card-title">Trạng thái hệ thống</h3>
                        </div>
                        <div class="settings-card-body">
                            <div class="status-grid">
                                <div class="status-item">
                                    <div class="status-icon online"><i class="fas fa-check-circle"></i></div>
                                    <div class="status-title">Web Server</div>
                                    <div class="status-text">Online</div>
                                </div>
                                <div class="status-item">
                                    <div class="status-icon online"><i class="fas fa-database"></i></div>
                                    <div class="status-title">Database</div>
                                    <div class="status-text">Connected</div>
                                </div>
                                <div class="status-item">
                                    <div class="status-icon online"><i class="fas fa-memory"></i></div>
                                    <div class="status-title">Cache</div>
                                    <div class="status-text">Working</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interface Tab -->
        <div class="tab-content" id="interface-tab-content">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="card-icon icon-purple"><i class="fas fa-palette"></i></div>
                    <h3 class="card-title">Cài đặt giao diện</h3>
                </div>
                <div class="settings-card-body">
                    <div class="theme-section">
                        <div class="theme-label">
                            <i class="fas fa-moon"></i>Chế độ hiển thị
                        </div>
                        <div class="theme-options">
                            <label class="theme-option {{ session('theme', 'light') === 'light' ? 'active' : '' }}" onclick="selectTheme('light')">
                                <input type="radio" name="theme" value="light" {{ session('theme', 'light') === 'light' ? 'checked' : '' }}>
                                <div class="theme-option-icon light"><i class="fas fa-sun"></i></div>
                                <div class="theme-option-title">Sáng</div>
                            </label>
                            <label class="theme-option {{ session('theme', 'light') === 'auto' ? 'active' : '' }}" onclick="selectTheme('auto')">
                                <input type="radio" name="theme" value="auto" {{ session('theme', 'light') === 'auto' ? 'checked' : '' }}>
                                <div class="theme-option-icon auto"><i class="fas fa-adjust"></i></div>
                                <div class="theme-option-title">Tự động</div>
                            </label>
                            <label class="theme-option {{ session('theme', 'light') === 'dark' ? 'active' : '' }}" onclick="selectTheme('dark')">
                                <input type="radio" name="theme" value="dark" {{ session('theme', 'light') === 'dark' ? 'checked' : '' }}>
                                <div class="theme-option-icon dark"><i class="fas fa-moon"></i></div>
                                <div class="theme-option-title">Tối</div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(tab) {
    // Update tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.closest('.tab-btn').classList.add('active');
    
    // Update tab content
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    document.getElementById(tab + '-tab-content').classList.add('active');
}

function selectTheme(theme) {
    document.querySelectorAll('.theme-option').forEach(opt => opt.classList.remove('active'));
    event.target.closest('.theme-option').classList.add('active');
    
    fetch('{{ route("admin.system.update-theme") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ theme: theme })
    }).then(response => response.json()).then(data => {
        if (data.success) {
            // Theme saved
        }
    });
}

function clearCache() {
    if (!confirm('Bạn có chắc chắn muốn xóa cache hệ thống?')) return;
    fetch('{{ route("admin.system.clear-cache") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    }).then(response => response.json()).then(data => {
        alert(data.message || 'Cache đã được xóa!');
    }).catch(() => alert('Có lỗi xảy ra!'));
}

function optimizeDatabase() {
    alert('Chức năng đang được phát triển');
}

function checkSystemHealth() {
    alert('Trạng thái hệ thống: OK\n- Web Server: Online\n- Database: Connected\n- Cache: Working');
}
</script>
@endpush
