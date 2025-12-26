@extends('layouts.app')

@section('title', 'Sửa giải đấu')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <h1 class="h3 mb-0">
                <i class="fas fa-edit me-2"></i>Sửa giải đấu
            </h1>
            <a href="{{ route('tournaments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="alert alert-info">
        Chức năng chỉnh sửa giải đấu đang được phát triển. Vui lòng quay lại sau.
    </div>
</div>
@endsection


