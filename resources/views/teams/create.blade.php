@extends('layouts.app')

@section('title', 'Tạo đội mới')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Tạo đội mới</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('teams.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên đội <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="game_id" class="form-label">Game</label>
                                    <select class="form-select @error('game_id') is-invalid @enderror"
                                        id="game_id" name="game_id">
                                        <option value="">-- Chọn game --</option>
                                        @foreach($games as $game)
                                        <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                                            {{ $game->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('game_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_members" class="form-label">Số thành viên tối đa</label>
                                    <input type="number" class="form-control @error('max_members') is-invalid @enderror"
                                        id="max_members" name="max_members" value="{{ old('max_members', 10) }}"
                                        min="2" max="20">
                                    @error('max_members')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Từ 2 đến 20 thành viên (mặc định: 10)</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo đội</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                id="logo" name="logo" accept="image/*">
                            @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Chấp nhận file JPG, PNG, GIF. Tối đa 2MB.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teams.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Tạo đội
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection