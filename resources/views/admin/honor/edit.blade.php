@extends('layouts.app')

@section('title', 'Chỉnh sửa đợt vote - ' . $honorEvent->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa đợt vote: {{ $honorEvent->name }}
                    </h6>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.honor.update', $honorEvent) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên đợt vote <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $honorEvent->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Đối tượng vote</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($honorEvent->target_type) }}" readonly>
                                    <small class="form-text text-muted">Không thể thay đổi đối tượng vote sau khi tạo</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Nhập mô tả về đợt vote...">{{ old('description', $honorEvent->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">Thời gian bắt đầu</label>
                                    <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" 
                                           id="start_time" name="start_time" 
                                           value="{{ old('start_time', $honorEvent->start_time ? $honorEvent->start_time->format('Y-m-d\TH:i') : '') }}">
                                    <small class="form-text text-muted">Để trống để bắt đầu ngay lập tức</small>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_time" class="form-label">Thời gian kết thúc</label>
                                    <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" 
                                           id="end_time" name="end_time" 
                                           value="{{ old('end_time', $honorEvent->end_time ? $honorEvent->end_time->format('Y-m-d\TH:i') : '') }}">
                                    <small class="form-text text-muted">Để trống để không giới hạn thời gian</small>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-gray-800 mb-3">Quyền vote theo role</h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="allow_viewer_vote" 
                                           name="allow_viewer_vote" value="1" 
                                           {{ old('allow_viewer_vote', $honorEvent->allow_viewer_vote) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allow_viewer_vote">
                                        <strong>Viewer</strong> - Người xem
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="allow_player_vote" 
                                           name="allow_player_vote" value="1" 
                                           {{ old('allow_player_vote', $honorEvent->allow_player_vote) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allow_player_vote">
                                        <strong>Player</strong> - Người chơi
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="allow_admin_vote" 
                                           name="allow_admin_vote" value="1" 
                                           {{ old('allow_admin_vote', $honorEvent->allow_admin_vote) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allow_admin_vote">
                                        <strong>Admin</strong> - Quản trị viên
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="allow_anonymous" 
                                           name="allow_anonymous" value="1" 
                                           {{ old('allow_anonymous', $honorEvent->allow_anonymous) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allow_anonymous">
                                        <strong>Cho phép vote ẩn danh</strong>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="text-gray-800 mb-3">Trọng số vote theo role</h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="viewer_weight" class="form-label">Trọng số Viewer <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('viewer_weight') is-invalid @enderror" 
                                           id="viewer_weight" name="viewer_weight" 
                                           value="{{ old('viewer_weight', $honorEvent->viewer_weight) }}" 
                                           step="0.1" min="0.1" max="10" required>
                                    @error('viewer_weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="player_weight" class="form-label">Trọng số Player <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('player_weight') is-invalid @enderror" 
                                           id="player_weight" name="player_weight" 
                                           value="{{ old('player_weight', $honorEvent->player_weight) }}" 
                                           step="0.1" min="0.1" max="10" required>
                                    @error('player_weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="admin_weight" class="form-label">Trọng số Admin <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('admin_weight') is-invalid @enderror" 
                                           id="admin_weight" name="admin_weight" 
                                           value="{{ old('admin_weight', $honorEvent->admin_weight) }}" 
                                           step="0.1" min="0.1" max="10" required>
                                    @error('admin_weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Lưu ý:</strong> Trọng số vote sẽ ảnh hưởng đến kết quả cuối cùng. 
                                    Ví dụ: 1 vote của Admin (trọng số 2.0) = 2 vote của Viewer (trọng số 1.0).
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.honor.index') }}" class="btn btn-secondary me-2">
                                        <i class="fas fa-arrow-left me-1"></i>Quay lại
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Cập nhật đợt vote
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    
    // Update end time minimum when start time changes
    startTimeInput.addEventListener('change', function() {
        if (this.value) {
            endTimeInput.min = this.value;
        }
    });
});
</script>
@endpush
