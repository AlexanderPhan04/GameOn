<div id="user-detail-root">
    <!-- Avatar & Basic Info -->
    <div class="profile-header">
        @php $avatarUrl = $user->getDisplayAvatar(); @endphp
        <div class="avatar-wrapper" style="width:70px;height:70px;margin:0 auto 0.75rem;position:relative;">
            @if($avatarUrl)
                <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" style="width:70px;height:70px;border-radius:50%;object-fit:cover;border:2px solid rgba(0,229,255,0.4);display:block;">
            @else
                <div class="profile-avatar-placeholder">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            @endif
            <span class="online-dot online-{{ $user->activity?->online_status ?? 'offline' }}"></span>
        </div>
        <h3 class="profile-name">{{ $user->profile?->full_name ?: $user->name }}</h3>
        <p class="profile-email">{{ $user->email }}</p>
        <div class="profile-badges">
            <span class="role-badge role-{{ $user->user_role }}">
                @if($user->user_role === 'super_admin') Super Admin
                @elseif($user->user_role === 'admin') Admin
                @else Participant @endif
            </span>
            <span class="status-badge status-{{ $user->status }}">{{ $user->status_display_name }}</span>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="info-cards">
        <div class="info-card">
            <div class="card-title"><i class="fas fa-user"></i> Thông tin cá nhân</div>
            <table class="info-table">
                <tr><td class="label">Username</td><td class="value">{{ $user->name }}</td></tr>
                <tr><td class="label">Họ tên</td><td class="value">{{ $user->profile?->full_name ?: '—' }}</td></tr>
                <tr><td class="label">ID App</td><td class="value">{{ $user->profile?->id_app ?: '—' }}</td></tr>
                <tr><td class="label">SĐT</td><td class="value">{{ $user->profile?->phone ?: '—' }}</td></tr>
                <tr><td class="label">Quốc gia</td><td class="value">{{ $user->profile?->country ?: '—' }}</td></tr>
                <tr><td class="label">Ngày sinh</td><td class="value">{{ $user->profile?->date_of_birth?->format('d/m/Y') ?: '—' }}</td></tr>
            </table>
        </div>

        <div class="info-card">
            <div class="card-title"><i class="fas fa-cog"></i> Tài khoản</div>
            <table class="info-table">
                <tr><td class="label">ID</td><td class="value">#{{ $user->id }}</td></tr>
                <tr><td class="label">Email</td><td class="value">@if($user->email_verified_at)<i class="fas fa-check-circle" style="color:#22c55e"></i>@else<i class="fas fa-times-circle" style="color:#64748b"></i>@endif {{ $user->email_verified_at ? 'Đã xác thực' : 'Chưa xác thực' }}</td></tr>
                <tr><td class="label">Đăng ký</td><td class="value">{{ $user->created_at->format('d/m/Y') }}</td></tr>
                <tr><td class="label">Login cuối</td><td class="value">{{ $user->activity?->last_login_at?->format('d/m/Y H:i') ?: '—' }}</td></tr>
            </table>
        </div>

        @if($user->google_id)
        <div class="info-card">
            <div class="card-title"><i class="fab fa-google"></i> Google</div>
            <table class="info-table">
                <tr><td class="label">Email</td><td class="value">{{ $user->google_email ?: '—' }}</td></tr>
            </table>
        </div>
        @endif

        @if($user->profile?->bio)
        <div class="info-card">
            <div class="card-title"><i class="fas fa-quote-left"></i> Tiểu sử</div>
            <p class="bio-content">{{ $user->profile->bio }}</p>
        </div>
        @endif
    </div>
</div>

<style>
#user-detail-root { padding: 0; }
.profile-header { text-align: center; padding: 1.25rem 1rem; background: linear-gradient(180deg, rgba(0,229,255,0.05) 0%, transparent 100%); border-bottom: 1px solid rgba(0,229,255,0.1); }
.avatar-wrapper { position: relative; display: inline-block; margin-bottom: 0.75rem; width: 70px; height: 70px; flex-shrink: 0; }
.profile-avatar { width: 70px; height: 70px; min-width: 70px; min-height: 70px; max-width: 70px; max-height: 70px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(0,229,255,0.4); display: block; overflow: hidden; }
.profile-avatar-placeholder { width: 70px; height: 70px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #4f46e5); display: flex; align-items: center; justify-content: center; font-size: 1.75rem; font-weight: 700; color: white; }
.online-dot { position: absolute; bottom: 2px; right: 2px; width: 14px; height: 14px; border-radius: 50%; border: 2px solid #0d1b2a; }
.online-dot.online-online { background: #22c55e; }
.online-dot.online-away { background: #f59e0b; }
.online-dot.online-busy { background: #ef4444; }
.online-dot.online-offline { background: #64748b; }
.profile-name { font-family: 'Rajdhani', sans-serif; font-size: 1.2rem; font-weight: 700; color: #fff; margin: 0 0 0.25rem; }
.profile-email { color: #64748b; font-size: 0.8rem; margin: 0 0 0.75rem; word-break: break-all; }
.profile-badges { display: flex; gap: 0.5rem; justify-content: center; }
.role-badge, .status-badge { padding: 0.25rem 0.6rem; border-radius: 4px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; }
.role-badge.role-super_admin { background: #ef4444; color: #fff; }
.role-badge.role-admin { background: #f59e0b; color: #000; }
.role-badge.role-participant { background: #6366f1; color: #fff; }
.role-badge.role-user { background: #64748b; color: #fff; }
.status-badge.status-active { background: rgba(34,197,94,0.2); color: #22c55e; }
.status-badge.status-suspended { background: rgba(245,158,11,0.2); color: #f59e0b; }
.status-badge.status-banned { background: rgba(239,68,68,0.2); color: #ef4444; }
.status-badge.status-deleted { background: rgba(100,116,139,0.2); color: #64748b; }

.info-cards { padding: 0.75rem; display: flex; flex-direction: column; gap: 0.5rem; }
.info-card { background: rgba(0,229,255,0.02); border: 1px solid rgba(0,229,255,0.08); border-radius: 8px; padding: 0.6rem 0.75rem; }
.card-title { color: #00E5FF; font-size: 0.7rem; font-weight: 600; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem; text-transform: uppercase; letter-spacing: 0.05em; }
.card-title i { font-size: 0.65rem; }
.info-table { width: 100%; border-collapse: collapse; }
.info-table tr + tr td { padding-top: 0.35rem; }
.info-table td { padding: 0.35rem 0; font-size: 0.8rem; vertical-align: top; line-height: 1.3; }
.info-table td.label { color: #64748b; width: 80px; padding-right: 0.5rem; }
.info-table td.value { color: #fff; word-break: break-word; }
.bio-content { color: #94a3b8; font-size: 0.8rem; line-height: 1.5; margin: 0; }
</style>
