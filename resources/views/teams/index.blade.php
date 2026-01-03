@extends('layouts.app')

@section('title', 'Đội của tôi')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-users me-2"></i>Đội của tôi</h2>
                <a href="{{ route('teams.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Tạo đội mới
                </a>
            </div>

            <!-- My Teams as Captain -->
            @php
            $myTeamsAsCaptain = $teams->where('captain_id', Auth::id());
            $myTeamsAsMember = Auth::user()->teams->where('captain_id', '!=', Auth::id());
            @endphp

            @if($myTeamsAsCaptain->count() > 0)
            <div class="mb-5">
                <h4 class="mb-3"><i class="fas fa-crown text-warning me-2"></i>Đội tôi làm đội trưởng</h4>
                <div class="row">
                    @foreach($myTeamsAsCaptain as $team)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm border-warning">
                            @if($team->logo_url)
                            <img src="{{ asset('uploads/' . $team->logo_url) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            @else
                            <div class="card-img-top bg-warning d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-users fa-4x text-white"></i>
                            </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">
                                    {{ $team->name }}
                                    <i class="fas fa-crown text-warning ms-1" title="Đội trưởng"></i>
                                </h5>
                                <p class="card-text">{{ Str::limit($team->description, 100) }}</p>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-users me-1"></i>{{ $team->members->count() }}/{{ $team->max_members ?? 'N/A' }} thành viên
                                    </small>
                                </div>
                                @if($team->game)
                                <div class="mb-2">
                                    <span class="badge bg-info">{{ $team->game->name }}</span>
                                </div>
                                @endif
                                <div class="mb-2">
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
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('teams.show', $team->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Xem
                                    </a>
                                    <a href="{{ route('teams.edit', $team->id) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit me-1"></i>Sửa
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- My Teams as Member -->
            @if($myTeamsAsMember->count() > 0)
            <div class="mb-5">
                <h4 class="mb-3"><i class="fas fa-user-friends text-primary me-2"></i>Đội tôi tham gia</h4>
                <div class="row">
                    @foreach($myTeamsAsMember as $team)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if($team->logo_url)
                            <img src="{{ asset('uploads/' . $team->logo_url) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-users fa-4x text-white"></i>
                            </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $team->name }}</h5>
                                <p class="card-text">{{ Str::limit($team->description, 100) }}</p>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-crown me-1"></i>{{ $team->captain->name ?? 'Chưa có đội trưởng' }}
                                    </small>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-users me-1"></i>{{ $team->members->count() }}/{{ $team->max_members ?? 'N/A' }} thành viên
                                    </small>
                                </div>
                                @if($team->game)
                                <div class="mb-2">
                                    <span class="badge bg-info">{{ $team->game->name }}</span>
                                </div>
                                @endif
                                <div class="mb-2">
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
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('teams.show', $team->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Xem
                                    </a>
                                    <form action="{{ route('teams.leave', $team->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc muốn rời khỏi đội này?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-sign-out-alt me-1"></i>Rời
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($myTeamsAsCaptain->count() == 0 && $myTeamsAsMember->count() == 0)
            <div class="row">
                @foreach($teams as $team)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($team->logo_url)
                        <img src="{{ asset('uploads/' . $team->logo_url) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        @else
                        <div class="card-img-top bg-primary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-users fa-4x text-white opacity-50"></i>
                        </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $team->name }}</h5>
                            <p class="card-text">{{ Str::limit($team->description, 100) }}</p>
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-crown me-1"></i>{{ $team->captain->display_name ?? 'Chưa có đội trưởng' }}
                                </small>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-users me-1"></i>{{ $team->members->count() }} thành viên
                                </small>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('teams.show', $team->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{ $teams->links() }}
            @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Bạn chưa tham gia đội nào</h4>
                <p class="text-muted mb-4">Tạo đội mới để bắt đầu hành trình esports của bạn!</p>
                <a href="{{ route('teams.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tạo đội đầu tiên
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection