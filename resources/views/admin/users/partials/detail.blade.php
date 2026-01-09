<div id="user-detail-root">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($user->avatar)
                        <img src="{{ get_avatar_url($user->avatar) }}" class="rounded-circle mb-3" width="120" height="120" alt="Avatar">
                        @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px; font-size: 48px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        @endif
                    </div>
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    <div class="mb-3">
                        <span class="badge @if($user->user_role === 'super_admin') bg-danger @elseif($user->user_role === 'admin') bg-warning @elseif($user->user_role === 'participant') bg-primary @else bg-secondary @endif me-2">
                            @if($user->user_role === 'super_admin') Super Admin
                            @elseif($user->user_role === 'admin') Admin
                            @elseif($user->user_role === 'participant') Participant
                            @else User
                            @endif
                        </span>
                        <span class="badge @if($user->status === 'active') bg-success @elseif($user->status === 'suspended') bg-warning @elseif($user->status === 'banned') bg-danger @else bg-secondary @endif">
                            @if($user->status === 'active') Hoạt động
                            @elseif($user->status === 'suspended') Tạm khóa
                            @elseif($user->status === 'banned') Cấm
                            @else Đã xóa
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6><i class="fas fa-user me-2"></i>Thông tin cá nhân</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Username</label>
                                <p class="mb-1">{{ $user->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Email</label>
                                <p class="mb-1">{{ $user->email }}</p>
                                @if($user->email_verified_at)
                                <small class="text-success"><i class="fas fa-check-circle me-1"></i>Email đã xác thực</small>
                                @else
                                <small class="text-warning"><i class="fas fa-exclamation-circle me-1"></i>Email chưa xác thực</small>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Họ và tên</label>
                                <p class="mb-1">{{ $user->full_name ?: 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Số điện thoại</label>
                                <p class="mb-1">{{ $user->phone ?: 'Chưa cập nhật' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Quốc gia</label>
                                <p class="mb-1">{{ $user->country ?: 'Chưa cập nhật' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Ngày sinh</label>
                                <p class="mb-1">{{ $user->date_of_birth?->format('d/m/Y') ?: 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($user->bio)
                    <div class="mb-3">
                        <label class="form-label small text-muted">Tiểu sử</label>
                        <p class="mb-1">{{ $user->bio }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6><i class="fas fa-cog me-2"></i>Thông tin tài khoản</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label small text-muted">ID</label>
                                <p class="mb-1">#{{ $user->id }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Vai trò</label>
                                <p class="mb-1"><span class="badge @if($user->user_role === 'super_admin') bg-danger @elseif($user->user_role === 'admin') bg-warning @elseif($user->user_role === 'participant') bg-primary @else bg-secondary @endif">{{ $user->role_display_name }}</span></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Trạng thái</label>
                                <p class="mb-1"><span class="badge @if($user->status === 'active') bg-success @elseif($user->status === 'suspended') bg-warning @elseif($user->status === 'banned') bg-danger @else bg-secondary @endif">{{ $user->status_display_name }}</span></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Ngày đăng ký</label>
                                <p class="mb-1">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Lần đăng nhập cuối</label>
                                <p class="mb-1">{{ $user->last_login ? $user->last_login->format('d/m/Y H:i') : 'Chưa đăng nhập' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

