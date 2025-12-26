@extends('layouts.app')

@section('title', 'Backup & Restore')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-database me-2"></i>Backup & Restore
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Backup & Restore</li>
            </ol>
        </nav>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Thao tác nhanh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-success w-100" onclick="createBackup()">
                                <i class="fas fa-plus-circle me-2"></i>Tạo Backup mới
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-outline-info w-100" onclick="refreshList()">
                                <i class="fas fa-sync-alt me-2"></i>Làm mới danh sách
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Backup List -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Danh sách Backup có sẵn
            </h6>
        </div>
        <div class="card-body">
            @if(count($backups) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th><i class="fas fa-file me-1"></i>Tên file</th>
                            <th><i class="fas fa-weight me-1"></i>Dung lượng</th>
                            <th><i class="fas fa-calendar me-1"></i>Ngày tạo</th>
                            <th><i class="fas fa-cogs me-1"></i>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($backups as $backup)
                        <tr>
                            <td>
                                <i class="fas fa-database me-2 text-primary"></i>
                                {{ $backup['name'] }}
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ number_format($backup['size'] / (1024 * 1024), 2) }} MB
                                </span>
                            </td>
                            <td>{{ $backup['created_at']->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.system.download-backup', $backup['name']) }}"
                                        class="btn btn-sm btn-outline-success"
                                        data-bs-toggle="tooltip"
                                        title="Tải xuống">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger"
                                        data-backup-name="{{ $backup['name'] }}"
                                        onclick="deleteBackup(this.dataset.backupName)"
                                        data-bs-toggle="tooltip"
                                        title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-database fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có backup nào</h5>
                <p class="text-muted">Tạo backup đầu tiên để bảo vệ dữ liệu của bạn</p>
                <button class="btn btn-primary" onclick="createBackup()">
                    <i class="fas fa-plus-circle me-2"></i>Tạo Backup ngay
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Information Panel -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card shadow border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                <i class="fas fa-info-circle me-1"></i>Thông tin quan trọng
                            </div>
                            <div class="text-gray-900">
                                <ul class="mb-0">
                                    <li>Backup được tạo tự động bao gồm toàn bộ database</li>
                                    <li>File backup được lưu trong thư mục storage/app/backups</li>
                                    <li>Nên tạo backup định kỳ để đảm bảo an toàn dữ liệu</li>
                                    <li>Backup cũ nên được lưu trữ ở nơi an toàn khác</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-info-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                <i class="fas fa-exclamation-triangle me-1"></i>Lưu ý an toàn
                            </div>
                            <div class="text-gray-900">
                                <ul class="mb-0">
                                    <li>Không xóa backup khi chưa có backup mới</li>
                                    <li>Kiểm tra file backup trước khi restore</li>
                                    <li>Backup không bao gồm file upload và log</li>
                                    <li>Thực hiện backup trong giờ ít người dùng</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function createBackup() {
        Swal.fire({
            title: 'Tạo Backup Database?',
            text: 'Quá trình này có thể mất vài phút. Bạn có muốn tiếp tục?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Tạo Backup',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch('{{ route("admin.system.create-backup") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message);
                        }
                        return data;
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Lỗi: ${error.message}`);
                    });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Thành công!',
                    text: result.value.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            }
        });
    }

    function deleteBackup(filename) {
        Swal.fire({
            title: 'Xóa Backup?',
            text: `Bạn có chắc chắn muốn xóa file backup "${filename}"? Thao tác này không thể hoàn tác!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Here you would make an AJAX call to delete the backup
                Swal.fire('Thông báo', 'Chức năng xóa backup đang được phát triển.', 'info');
            }
        });
    }

    function refreshList() {
        location.reload();
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection