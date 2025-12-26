@extends('layouts.app')

@section('title', 'Chỉnh sửa người dùng - ' . $user->display_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 page-hero">
                <div>
                    <h3 class="mb-1">Chỉnh sửa người dùng</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Quản lý người dùng</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.show', $user) }}">{{ $user->display_name }}</a></li>
                            <li class="breadcrumb-item active">Chỉnh sửa</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- User Avatar -->
                    <div class="col-md-4">
                        <div class="card card-modern h-100">
                            <div class="card-header">
                                <h6><i class="fas fa-user-circle me-2"></i>Ảnh đại diện</h6>
                            </div>
                            <div class="card-body text-center">
                                @if($user->avatar)
                                <img src="{{ $user->avatar_url }}"
                                    class="rounded-circle mb-3"
                                    width="120"
                                    height="120"
                                    alt="Avatar">
                                @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                                    style="width: 120px; height: 120px; font-size: 48px;">
                                    {{ strtoupper(substr($user->display_name, 0, 1)) }}
                                </div>
                                @endif

                                <p class="text-muted small">
                                    Ảnh đại diện không thể thay đổi từ trang admin.<br>
                                    Người dùng cần tự cập nhật trong profile.
                                </p>
                            </div>
                        </div>

                        <!-- Current Status -->
                        <div class="card card-modern mt-3">
                            <div class="card-header">
                                <h6><i class="fas fa-info-circle me-2"></i>Trạng thái hiện tại</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <small class="text-muted">Vai trò:</small><br>
                                    <span class="badge {{ $user->role_badge_class }}">
                                        {{ $user->role_display_name }}
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Trạng thái:</small><br>
                                    <span class="badge {{ $user->status_badge_class }}">
                                        {{ $user->status_display_name }}
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Ngày tạo:</small><br>
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Form -->
                    <div class="col-md-8">
                        <div class="card card-modern">
                            <div class="card-header">
                                <h6><i class="fas fa-edit me-2"></i>Thông tin tài khoản</h6>
                            </div>
                            <div class="card-body">
                                <!-- Basic Information -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Username <span class="text-danger">*</span></label>
                                            <div class="input-group input-with-icon">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    id="name"
                                                    name="name"
                                                    value="{{ old('name', $user->name) }}"
                                                    placeholder="Nhập username"
                                                    required>
                                            </div>
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <div class="input-group input-with-icon">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="email"
                                                    name="email"
                                                    value="{{ old('email', $user->email) }}"
                                                    placeholder="email@domain.com"
                                                    required>
                                            </div>
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="full_name" class="form-label">Họ và tên</label>
                                            <div class="input-group input-with-icon">
                                                <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                                <input type="text"
                                                    class="form-control @error('full_name') is-invalid @enderror"
                                                    id="full_name"
                                                    name="full_name"
                                                    value="{{ old('full_name', $user->full_name) }}"
                                                    placeholder="Họ và tên">
                                            </div>
                                            @error('full_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Số điện thoại</label>
                                            <div class="input-group input-with-icon">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                <input type="text"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    id="phone"
                                                    name="phone"
                                                    value="{{ old('phone', $user->phone) }}"
                                                    placeholder="Số điện thoại">
                                            </div>
                                            @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="country" class="form-label">Quốc gia</label>
                                    <div class="input-group input-with-icon">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                        <input type="text"
                                            class="form-control @error('country') is-invalid @enderror"
                                            id="country"
                                            name="country"
                                            value="{{ old('country', $user->country) }}"
                                            placeholder="Quốc gia">
                                    </div>
                                    @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="bio" class="form-label">Tiểu sử</label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror auto-resize"
                                        id="bio"
                                        name="bio"
                                        rows="3">{{ old('bio', $user->bio) }}</textarea>
                                    @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr>

                                <!-- Role & Status -->
                                <h6 class="mb-3">Vai trò và trạng thái</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="user_role" class="form-label">Vai trò <span class="text-danger">*</span></label>
                                            <select class="form-select @error('user_role') is-invalid @enderror nice-select"
                                                id="user_role"
                                                name="user_role"
                                                required>
                                                @if(Auth::user()->isSuperAdmin())
                                                <option value="super_admin" {{ old('user_role', $user->user_role) === 'super_admin' ? 'selected' : '' }}>
                                                    Super Admin
                                                </option>
                                                @endif
                                                <option value="admin" {{ old('user_role', $user->user_role) === 'admin' ? 'selected' : '' }}>
                                                    Admin
                                                </option>
                                                <option value="player" {{ old('user_role', $user->user_role) === 'player' ? 'selected' : '' }}>
                                                    Player
                                                </option>
                                                <option value="viewer" {{ old('user_role', $user->user_role) === 'viewer' ? 'selected' : '' }}>
                                                    Viewer
                                                </option>
                                            </select>
                                            @error('user_role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @if(!Auth::user()->isSuperAdmin())
                                            <small class="text-muted">Bạn không thể thiết lập vai trò Super Admin</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror nice-select"
                                                id="status"
                                                name="status"
                                                required>
                                                <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>
                                                    Hoạt động
                                                </option>
                                                <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>
                                                    Tạm khóa
                                                </option>
                                                <option value="banned" {{ old('status', $user->status) === 'banned' ? 'selected' : '' }}>
                                                    Cấm vĩnh viễn
                                                </option>
                                                <option value="deleted" {{ old('status', $user->status) === 'deleted' ? 'selected' : '' }}>
                                                    Đã xóa
                                                </option>
                                            </select>
                                            @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Information -->
                                @if($user->google_id || $user->email_verified_at)
                                <hr>
                                <h6 class="mb-3">Thông tin bổ sung</h6>
                                <div class="row">
                                    @if($user->google_id)
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Liên kết Google</label>
                                            <p class="form-control-plaintext">
                                                <i class="fab fa-google text-danger me-2"></i>
                                                Đã liên kết (ID: {{ $user->google_id }})
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                    @if($user->email_verified_at)
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email đã xác thực</label>
                                            <p class="form-control-plaintext">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                {{ $user->email_verified_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>

                            <div class="card-footer">
                                <small class="text-muted d-block"><i class="fas fa-info-circle me-1"></i>Các trường có dấu * là bắt buộc</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sticky action bar -->
                <div class="editor-sticky-bar">
                    <div class="container-fluid px-0 d-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-flex align-items-center text-muted small">
                            <i class="fas fa-shield-alt me-2"></i>Thay đổi sẽ được ghi lại trong lịch sử hoạt động
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-light me-2"><i class="fas fa-times me-1"></i>Hủy</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Cập nhật</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-hero{border-radius:16px;padding:1rem 1.25rem;background:linear-gradient(180deg,#ffffff 0%,#f6fbff 100%);border:1px solid #eef0f3}
    .page-hero h3{font-weight:700;color:#0f172a;font-size:1.5rem}
    .page-hero .breadcrumb{margin:0;background:transparent;padding:0}
    .page-hero .breadcrumb .breadcrumb-item a{color:#334155;text-decoration:none}
    .page-hero .breadcrumb .breadcrumb-item a:hover{color:#0d6efd}
    .page-hero .breadcrumb .breadcrumb-item+.breadcrumb-item::before{color:#94a3b8}
    .page-hero .breadcrumb .active{color:#64748b}
    .page-hero .btn-outline-secondary{background:#ffffff;border-color:#cbd5e1;color:#334155}
    .page-hero .btn-outline-secondary:hover{background:#f8fafc;border-color:#94a3b8;color:#0f172a}
    .card-modern{border:1px solid #eef0f3;border-radius:14px;box-shadow:0 6px 18px rgba(16,24,40,.04)}
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .card-header h6 {
        margin-bottom: 0;
        font-weight: 600;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .input-with-icon .input-group-text{background:#f8fafc;border-right:0}
    .input-with-icon .form-control{border-left:0}
    .auto-resize{min-height:90px}

    .nice-select{border-radius:10px}

    .editor-sticky-bar{position:sticky;bottom:0;left:0;right:0;background:rgba(255,255,255,.9);backdrop-filter:saturate(180%) blur(8px);border-top:1px solid #e5e7eb;padding:.75rem 1rem;margin-top:1rem;z-index:1030}
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Show confirmation when changing critical fields
        $('#user_role, #status').change(function() {
            const field = $(this);
            const fieldName = field.attr('id') === 'user_role' ? 'vai trò' : 'trạng thái';
            const newValue = field.find('option:selected').text();

            if (!confirm(`Bạn có chắc chắn muốn thay đổi ${fieldName} thành "${newValue}"?`)) {
                field.val(field.data('original-value'));
            }
        });

        // Store original values
        $('#user_role, #status').each(function() {
            $(this).data('original-value', $(this).val());
        });
    });
</script>
@endpush