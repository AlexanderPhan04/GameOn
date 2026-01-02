@extends('layouts.app')

@section('title', 'Chi tiết người dùng - ' . $user->name)

@section('content')
<div class="container-fluid" id="user-detail-root">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            {{-- This full page is deprecated. Redirected by controller. Keep minimal markup for compatibility. --}}

            <div class="row">
                <!-- User Profile Card -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <!-- Avatar -->
                            <div class="mb-3">
                                @if($user->avatar)
                                <img src="{{ get_avatar_url($user->avatar) }}"
                                    class="rounded-circle mb-3"
                                    width="120"
                                    height="120"
                                    alt="Avatar">
                                @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                                    style="width: 120px; height: 120px; font-size: 48px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                @endif
                            </div>

                            <!-- Basic Info -->
                            <h4>{{ $user->name }}</h4>
                            <p class="text-muted">{{ $user->email }}</p>

                            <!-- Role & Status Badges -->
                            <div class="mb-3">
                                <span class="badge 
                                    @if($user->user_role === 'super_admin') bg-danger
                                    @elseif($user->user_role === 'admin') bg-warning
                                    @elseif($user->user_role === 'participant') bg-primary
                                    @else bg-secondary
                                    @endif me-2">
                                    @if($user->user_role === 'super_admin') Super Admin
                                    @elseif($user->user_role === 'admin') Admin
                                    @elseif($user->user_role === 'participant') Participant
                                    @else User
                                    @endif
                                </span>
                                <span class="badge 
                                    @if($user->status === 'active') bg-success
                                    @elseif($user->status === 'suspended') bg-warning
                                    @elseif($user->status === 'banned') bg-danger
                                    @else bg-secondary
                                    @endif">
                                    @if($user->status === 'active') Hoạt động
                                    @elseif($user->status === 'suspended') Tạm khóa
                                    @elseif($user->status === 'banned') Cấm
                                    @else Đã xóa
                                    @endif
                                </span>
                            </div>

                            <!-- Quick Actions -->
                            @php
                            $currentUser = Auth::user();
                            $canManageStatus = false;

                            // Super Admin có thể quản lý tất cả trừ chính mình
                            if ($currentUser->user_role === 'super_admin' && $user->id !== $currentUser->id) {
                            $canManageStatus = true;
                            }
                            // Admin chỉ có thể quản lý Participant
                            elseif ($currentUser->user_role === 'admin' &&
                            $user->id !== $currentUser->id &&
                            !in_array($user->user_role, ['super_admin', 'admin'])) {
                            $canManageStatus = true;
                            }
                            @endphp

                            @if($canManageStatus)
                            <div class="d-grid gap-2">
                                @if($user->status === 'active')
                                <button class="btn btn-warning btn-sm" onclick="changeStatus('suspended')">
                                    <i class="fas fa-pause me-2"></i>Tạm khóa
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="changeStatus('banned')">
                                    <i class="fas fa-ban me-2"></i>Cấm tài khoản
                                </button>
                                @elseif($user->status === 'suspended')
                                <button class="btn btn-success btn-sm" onclick="changeStatus('active')">
                                    <i class="fas fa-check me-2"></i>Kích hoạt
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="changeStatus('banned')">
                                    <i class="fas fa-ban me-2"></i>Cấm tài khoản
                                </button>
                                @elseif($user->status === 'banned')
                                <button class="btn btn-success btn-sm" onclick="changeStatus('active')">
                                    <i class="fas fa-check me-2"></i>Kích hoạt
                                </button>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Account Stats -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6><i class="fas fa-chart-bar me-2"></i>Thống kê tài khoản</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h5 class="text-primary">{{ $user->teams->count() }}</h5>
                                        <small class="text-muted">Teams</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h5 class="text-success">{{ $user->captainTeams->count() }}</h5>
                                    <small class="text-muted">Captain Teams</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Details -->
                <div class="col-md-8">
                    <!-- Personal Information -->
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
                                        <small class="text-success">
                                            <i class="fas fa-check-circle me-1"></i>Email đã xác thực
                                        </small>
                                        @else
                                        <small class="text-warning">
                                            <i class="fas fa-exclamation-circle me-1"></i>Email chưa xác thực
                                        </small>
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

                    <!-- Account Information -->
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
                                        <p class="mb-1">
                                            <span class="badge 
                                                @if($user->user_role === 'super_admin') bg-danger
                                                @elseif($user->user_role === 'admin') bg-warning
                                                @elseif($user->user_role === 'participant') bg-primary
                                                @else bg-secondary
                                                @endif">
                                                @if($user->user_role === 'super_admin') Super Admin
                                                @elseif($user->user_role === 'admin') Admin
                                                @elseif($user->user_role === 'participant') Participant
                                                @else User
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Trạng thái</label>
                                        <p class="mb-1">
                                            <span class="badge 
                                                @if($user->status === 'active') bg-success
                                                @elseif($user->status === 'suspended') bg-warning
                                                @elseif($user->status === 'banned') bg-danger
                                                @else bg-secondary
                                                @endif">
                                                @if($user->status === 'active') Hoạt động
                                                @elseif($user->status === 'suspended') Tạm khóa
                                                @elseif($user->status === 'banned') Cấm
                                                @else Đã xóa
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Ngày đăng ký</label>
                                        <p class="mb-1">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Lần đăng nhập cuối</label>
                                        <p class="mb-1">
                                            {{ $user->last_login ? $user->last_login->format('d/m/Y H:i') : 'Chưa đăng nhập' }}
                                        </p>
                                    </div>
                                    @if($user->google_id)
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Liên kết Google</label>
                                        <p class="mb-1">
                                            <i class="fab fa-google text-danger me-2"></i>
                                            Đã liên kết
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Teams Information -->
                    @if($user->teams->count() > 0)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6><i class="fas fa-users me-2"></i>Các đội tham gia</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tên đội</th>
                                            <th>Vai trò</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày tham gia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->teams as $team)
                                        <tr>
                                            <td>{{ $team->name }}</td>
                                            <td>
                                                @if($team->captain_id === $user->id)
                                                <span class="badge bg-warning">Đội trưởng</span>
                                                @else
                                                {{ $team->pivot->role }}
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $team->pivot->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($team->pivot->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $team->pivot->joined_at?->format('d/m/Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection