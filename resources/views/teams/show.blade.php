@extends('layouts.app')

@section('title', $team->name)

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="d-flex align-items-center">
                            @if($team->logo_url)
                            <img src="{{ asset('uploads/' . $team->logo_url) }}" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                            <div class="bg-primary rounded d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-users fa-2x text-white"></i>
                            </div>
                            @endif
                            <div>
                                <h2 class="mb-1">{{ $team->name }}</h2>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-crown me-1"></i>{{ $team->captain->display_name ?? 'Chưa có đội trưởng' }}
                                </p>
                            </div>
                        </div>
                        @can('update', $team)
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cog me-1"></i>Quản lý
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('teams.edit', $team->id) }}">
                                        <i class="fas fa-edit me-2"></i>Chỉnh sửa
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('teams.destroy', $team->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa đội này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-trash me-2"></i>Xóa đội
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        @endcan
                    </div>

                    @if($team->description)
                    <div class="mb-4">
                        <h5>Mô tả</h5>
                        <p>{{ $team->description }}</p>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                    <h4>{{ $team->members->count() }}</h4>
                                    <p class="mb-0">Thành viên</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar fa-2x text-success mb-2"></i>
                                    <h4>{{ $team->created_at->format('d/m/Y') }}</h4>
                                    <p class="mb-0">Ngày tạo</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Thành viên ({{ $team->members->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    @if($team->members->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($team->members as $member)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                @if($member->user->avatar)
                                <img src="{{ get_avatar_url($member->user->avatar) }}" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $member->user->display_name }}</div>
                                    <small class="text-muted">{{ $member->user->app_id }}</small>
                                </div>
                            </div>
                            @if($member->user_id === $team->captain_id)
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-crown me-1"></i>Đội trưởng
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Chưa có thành viên nào</p>
                    </div>
                    @endif
                </div>
            </div>

            @auth
            @if(Auth::user()->user_role === 'participant')
            <div class="card shadow mt-3">
                <div class="card-body text-center">
                    @php
                    $isMember = $team->members->where('user_id', Auth::id())->first();
                    @endphp

                    @if(!$isMember)
                    <h6>Tham gia đội</h6>
                    <p class="text-muted small mb-3">Bạn có muốn tham gia đội này không?</p>
                    <form action="{{ route('teams.join', $team->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Tham gia
                        </button>
                    </form>
                    @else
                    <h6>Bạn đã là thành viên</h6>
                    <p class="text-muted small mb-3">Bạn đã tham gia đội này</p>
                    <form action="{{ route('teams.leave', $team->id) }}" method="POST"
                        onsubmit="return confirm('Bạn có chắc muốn rời khỏi đội?')">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i>Rời đội
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endif
            @endauth
        </div>
    </div>
</div>
@endsection