@extends('layouts.app')

@section('title', 'Chi tiết đội - ' . $team->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3>Chi tiết đội</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.teams.index') }}">Quản lý đội</a></li>
                            <li class="breadcrumb-item active">{{ $team->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.teams.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Team Profile Card -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <!-- Logo -->
                            <div class="mb-3">
                                @if($team->logo_url)
                                <img src="{{ asset('uploads/' . $team->logo_url) }}"
                                    class="rounded mb-3"
                                    width="120"
                                    height="120"
                                    alt="Logo">
                                @else
                                <div class="rounded bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                                    style="width: 120px; height: 120px; font-size: 48px;">
                                    {{ strtoupper(substr($team->name, 0, 2)) }}
                                </div>
                                @endif
                            </div>

                            <!-- Basic Info -->
                            <h4>{{ $team->name }}</h4>
                            @if($team->description)
                            <p class="text-muted">{{ $team->description }}</p>
                            @endif

                            <!-- Status Badge -->
                            <div class="mb-3">
                                <span class="badge 
                                    @if($team->status === 'active') bg-success
                                    @elseif($team->status === 'inactive') bg-secondary
                                    @elseif($team->status === 'suspended') bg-warning
                                    @else bg-danger
                                    @endif">
                                    @if($team->status === 'active') Hoạt động
                                    @elseif($team->status === 'inactive') Không hoạt động
                                    @elseif($team->status === 'suspended') Tạm khóa
                                    @else Cấm
                                    @endif
                                </span>
                            </div>

                            <!-- Quick Actions -->
                            <div class="d-grid gap-2">
                                @if($team->status === 'active')
                                <button class="btn btn-warning btn-sm" onclick="changeStatus('suspended')">
                                    <i class="fas fa-pause me-2"></i>Tạm khóa
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="changeStatus('banned')">
                                    <i class="fas fa-ban me-2"></i>Cấm đội
                                </button>
                                @elseif($team->status === 'suspended')
                                <button class="btn btn-success btn-sm" onclick="changeStatus('active')">
                                    <i class="fas fa-check me-2"></i>Kích hoạt
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="changeStatus('banned')">
                                    <i class="fas fa-ban me-2"></i>Cấm đội
                                </button>
                                @elseif($team->status === 'banned')
                                <button class="btn btn-success btn-sm" onclick="changeStatus('active')">
                                    <i class="fas fa-check me-2"></i>Kích hoạt
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Team Stats -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6><i class="fas fa-chart-bar me-2"></i>Thống kê đội</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h5 class="text-primary">{{ $team->members->count() }}</h5>
                                        <small class="text-muted">Thành viên</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h5 class="text-success">{{ $team->max_members ?? 'N/A' }}</h5>
                                    <small class="text-muted">Tối đa</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Details -->
                <div class="col-md-8">
                    <!-- Basic Information -->
                    <div class="card">
                        <div class="card-header">
                            <h6><i class="fas fa-info-circle me-2"></i>Thông tin cơ bản</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Tên đội</label>
                                        <p class="mb-1">{{ $team->name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Game</label>
                                        <p class="mb-1">
                                            @if($team->game)
                                            <span class="badge bg-info">{{ $team->game->name }}</span>
                                            @else
                                            <span class="text-muted">Chưa chọn</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Đội trưởng</label>
                                        <p class="mb-1">
                                            @if($team->captain)
                                            {{ $team->captain->name }}
                                            <br><small class="text-muted">{{ $team->captain->email }}</small>
                                            @else
                                            <span class="text-muted">Chưa có</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Người tạo</label>
                                        <p class="mb-1">
                                            @if($team->creator)
                                            {{ $team->creator->name }}
                                            <br><small class="text-muted">{{ $team->creator->email }}</small>
                                            @else
                                            <span class="text-muted">Không có thông tin</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Ngày tạo</label>
                                        <p class="mb-1">{{ $team->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Cập nhật cuối</label>
                                        <p class="mb-1">{{ $team->updated_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($team->description)
                            <div class="mb-3">
                                <label class="form-label small text-muted">Mô tả</label>
                                <p class="mb-1">{{ $team->description }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Team Members -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6><i class="fas fa-users me-2"></i>Danh sách thành viên ({{ $team->members->count() }})</h6>
                        </div>
                        <div class="card-body">
                            @if($team->members->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Thành viên</th>
                                            <th>Email</th>
                                            <th>Vai trò</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày tham gia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($team->members as $member)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                                        style="width: 32px; height: 32px; font-size: 12px;">
                                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        {{ $member->name }}
                                                        @if($member->id === $team->captain_id)
                                                        <i class="fas fa-crown text-warning ms-1" title="Đội trưởng"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $member->email }}</td>
                                            <td>
                                                @if($member->id === $team->captain_id)
                                                <span class="badge bg-warning">Đội trưởng</span>
                                                @else
                                                <span class="badge bg-secondary">{{ $member->pivot->role ?? 'Thành viên' }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $member->pivot->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $member->pivot->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                                                </span>
                                            </td>
                                            <td>{{ $member->pivot->joined_at ? \Carbon\Carbon::parse($member->pivot->joined_at)->format('d/m/Y') : 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Đội chưa có thành viên nào</p>
                            </div>
                            @endif
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
    function changeStatus(status) {
        const statusNames = {
            'active': 'kích hoạt',
            'suspended': 'tạm khóa',
            'banned': 'cấm'
        };

        if (!confirm(`Bạn có chắc chắn muốn ${statusNames[status]} đội này?`)) {
            return;
        }

        $.ajax({
            url: `{{ route('admin.teams.update-status', $team) }}`,
            method: 'PATCH',
            data: {
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            },
            error: function() {
                showNotification('error', 'Có lỗi xảy ra khi cập nhật trạng thái');
            }
        });
    }

    function showNotification(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

        $('body').prepend(alertHtml);

        setTimeout(() => {
            $('.alert').first().alert('close');
        }, 5000);
    }
</script>
@endpush