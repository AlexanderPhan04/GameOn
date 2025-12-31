@extends('layouts.app')

@section('title', __('app.honor.page_title'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h5 class="m-0 text-dark fw-bold">{{ __('app.honor.page_title') }}</h5>
                        </div>
                        @auth
                            @if(auth()->user()->user_role === 'super_admin')
                                <a href="{{ route('admin.honor.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>{{ __('app.honor.create_event') }}
                                </a>
                            @endif
                        @endauth
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Current Event -->
                    @if($currentEvent)
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="small text-uppercase text-primary fw-bold mb-1">
                                                    {{ __('app.honor.current_event') }}
                                                </div>
                                                <div class="h4 mb-1 fw-bold text-dark">
                                                    {{ $currentEvent->name }}
                                                </div>
                                                @if($currentEvent->description)
                                                    <div class="text-muted small mt-1">
                                                        {{ $currentEvent->description }}
                                                    </div>
                                                @endif
                                                <div class="mt-3 d-flex gap-2 align-items-center">
                                                    <span class="badge badge-{{ $currentEvent->mode === 'event' ? 'warning' : 'success' }}">
                                                        {{ $currentEvent->mode === 'event' ? __('app.honor.mode_event') : __('app.honor.mode_free') }}
                                                    </span>
                                                    <span class="badge badge-info">
                                                        {{ ucfirst($currentEvent->target_type) }}
                                                    </span>
                                                    @if($currentEvent->isCurrentlyRunning())
                                                        <span class="badge badge-success">{{ __('app.honor.status_active') }}</span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ __('app.honor.status_off') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                @if($currentEvent->isCurrentlyRunning() && auth()->check() && $currentEvent->canUserVote(auth()->user()))
                                                    <a href="{{ route('honor.show', $currentEvent) }}" class="btn btn-primary btn-lg">
                                                        <i class="fas fa-vote-yea me-1"></i>{{ __('app.honor.join_vote') }}
                                                    </a>
                                                @else
                                                    <a href="{{ route('honor.results', $currentEvent) }}" class="btn btn-outline-primary btn-lg">
                                                        <i class="fas fa-chart-bar me-1"></i>{{ __('app.honor.view_results') }}
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Free Mode Events -->
                    @if($freeModeEvents->count() > 0)
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-gray-800 mb-3">
                                    <i class="fas fa-list me-2"></i>{{ __('app.honor.free_events') }}
                                </h5>
                            </div>
                        </div>
                        <div class="row">
                            @foreach($freeModeEvents as $event)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <h6 class="card-title text-primary">{{ $event->name }}</h6>
                                            @if($event->description)
                                                <p class="card-text text-muted small">{{ Str::limit($event->description, 100) }}</p>
                                            @endif
                                            <div class="mb-2">
                                                <span class="badge badge-info">{{ ucfirst($event->target_type) }}</span>
                                                @if($event->isCurrentlyRunning())
                                                    <span class="badge badge-success">{{ __('app.honor.status_active') }}</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ __('app.honor.status_paused') }}</span>
                                                @endif
                                            </div>
                                            <div class="text-muted small mb-3">
                                                <i class="fas fa-users me-1"></i>
                                                {{ $event->getTotalVotesCount() }} {{ __('app.honor.votes') }}
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent d-flex gap-2 p-3">
                                            @if($event->isCurrentlyRunning() && auth()->check() && $event->canUserVote(auth()->user()))
                                                <a href="{{ route('honor.show', $event) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas a fa-vote-yea me-1"></i>{{ __('app.honor.vote_btn') }}
                                                </a>
                                            @endif
                                            <a href="{{ route('honor.results', $event) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-chart-bar me-1"></i>{{ __('app.honor.view_results') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('app.honor.empty_title') }}</h5>
                            <p class="text-muted">{{ __('app.honor.empty_desc') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
