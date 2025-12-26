@extends('layouts.app')

@section('title', 'Chỉnh sửa đội: ' . $team->name)

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa đội: {{ $team->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('teams.update', $team->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên đội <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $team->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="4">{{ old('description', $team->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo đội</label>
                            @if($team->logo_url)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $team->logo_url) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                <p class="small text-muted mt-1">Logo hiện tại</p>
                            </div>
                            @endif
                            <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                id="logo" name="logo" accept="image/*">
                            @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Chấp nhận file JPG, PNG, GIF. Tối đa 2MB. Để trống nếu không muốn thay đổi.</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teams.show', $team->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection