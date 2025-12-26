@extends('layouts.app')

@section('title', __('app.honor.create_event'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-plus me-2"></i>{{ __('app.honor.create_event') }}
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

                    <form action="{{ route('admin.honor.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('app.honor.col_event_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="target_type" class="form-label">{{ __('app.honor.col_target') }} <span class="text-danger">*</span></label>
                                    <select class="form-control @error('target_type') is-invalid @enderror" 
                                            id="target_type" name="target_type" required>
                                        <option value="">{{ __('app.common.select') }}</option>
                                        <option value="player" {{ old('target_type') === 'player' ? 'selected' : '' }}>Player</option>
                                        <option value="team" {{ old('target_type') === 'team' ? 'selected' : '' }}>Team</option>
                                        <option value="tournament" {{ old('target_type') === 'tournament' ? 'selected' : '' }}>Tournament</option>
                                        <option value="game" {{ old('target_type') === 'game' ? 'selected' : '' }}>Game</option>
                                    </select>
                                    @error('target_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('app.common.description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="{{ __('app.common.description') }}">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mode" class="form-label">{{ __('app.honor.col_mode') }} <span class="text-danger">*</span></label>
                                    <select class="form-control @error('mode') is-invalid @enderror" 
                                            id="mode" name="mode" required>
                                        <option value="free" {{ old('mode') === 'free' ? 'selected' : '' }}>{{ __('app.honor.mode_free') }} (Free mode)</option>
                                        <option value="event" {{ old('mode') === 'event' ? 'selected' : '' }}>{{ __('app.honor.mode_event') }} (Event mode)</option>
                                    </select>
                                    @error('mode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="allow_anonymous" class="form-label">{{ __('app.common.allow_anonymous') }}</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="allow_anonymous" 
                                               name="allow_anonymous" value="1" {{ old('allow_anonymous') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="allow_anonymous">
                                            {{ __('app.common.allow_anonymous') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">{{ __('app.honor.time_from') }}</label>
                                    <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" 
                                           id="start_time" name="start_time" value="{{ old('start_time') }}">
                                    <small class="form-text text-muted">{{ __('app.common.leave_blank_immediate') }}</small>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_time" class="form-label">{{ __('app.honor.time_to') }}</label>
                                    <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" 
                                           id="end_time" name="end_time" value="{{ old('end_time') }}">
                                    <small class="form-text text-muted">{{ __('app.honor.time_unlimited') }}</small>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-gray-800 mb-3">{{ __('app.common.permissions_by_role') }}</h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="allow_viewer_vote" 
                                           name="allow_viewer_vote" value="1" {{ old('allow_viewer_vote', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allow_viewer_vote">
                                        <strong>Viewer</strong> - {{ __('app.auth.viewer') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="allow_player_vote" 
                                           name="allow_player_vote" value="1" {{ old('allow_player_vote', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allow_player_vote">
                                        <strong>Player</strong> - {{ __('app.auth.player') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="allow_admin_vote" 
                                           name="allow_admin_vote" value="1" {{ old('allow_admin_vote', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allow_admin_vote">
                                        <strong>Admin</strong> - {{ __('app.profile.manage_users') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="text-gray-800 mb-3">{{ __('app.common.weights_by_role') }}</h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="viewer_weight" class="form-label">Viewer weight <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('viewer_weight') is-invalid @enderror" 
                                           id="viewer_weight" name="viewer_weight" value="{{ old('viewer_weight', 1.0) }}" 
                                           step="0.1" min="0.1" max="10" required>
                                    @error('viewer_weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="player_weight" class="form-label">Player weight <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('player_weight') is-invalid @enderror" 
                                           id="player_weight" name="player_weight" value="{{ old('player_weight', 1.5) }}" 
                                           step="0.1" min="0.1" max="10" required>
                                    @error('player_weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="admin_weight" class="form-label">Admin weight <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('admin_weight') is-invalid @enderror" 
                                           id="admin_weight" name="admin_weight" value="{{ old('admin_weight', 2.0) }}" 
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
                                    <strong>{{ __('app.common.note') }}</strong> {{ __('app.common.weight_note') }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.honor.index') }}" class="btn btn-secondary me-2">
                                        <i class="fas fa-arrow-left me-1"></i>{{ __('app.common.back') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>{{ __('app.common.create') }}
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
    
    // Set minimum start time to now
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    startTimeInput.min = now.toISOString().slice(0, 16);
    
    // Update end time minimum when start time changes
    startTimeInput.addEventListener('change', function() {
        if (this.value) {
            endTimeInput.min = this.value;
        }
    });
});
</script>
@endpush
