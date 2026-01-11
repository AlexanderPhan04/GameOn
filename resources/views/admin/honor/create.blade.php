@extends('layouts.app')

@section('title', __('app.honor.create_event'))

@push('styles')
<style>
    .honor-create-container { background: #000814; min-height: 100vh; }

    /* Hero Section */
    .create-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(245, 158, 11, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .create-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #f59e0b, transparent);
    }
    .hero-icon {
        width: 60px; height: 60px; min-width: 60px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 0 25px rgba(245, 158, 11, 0.3);
    }
    .hero-icon i { font-size: 1.5rem; color: white; }
    .hero-title { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; color: #f59e0b; margin: 0; }
    .hero-subtitle { color: #94a3b8; font-size: 0.9rem; margin: 0.25rem 0 0 0; }

    .btn-neon {
        background: linear-gradient(135deg, #000055, #000077);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.4);
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-neon:hover {
        background: rgba(0, 229, 255, 0.15);
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.4);
        color: #FFFFFF;
        transform: translateY(-2px);
    }
    .btn-neon-gold {
        background: linear-gradient(135deg, #92400e, #b45309);
        border-color: rgba(245, 158, 11, 0.4);
        color: #fbbf24;
    }
    .btn-neon-gold:hover { box-shadow: 0 0 20px rgba(245, 158, 11, 0.4); color: #FFFFFF; }

    /* Form Card */
    .form-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(245, 158, 11, 0.15);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .form-card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(245, 158, 11, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .card-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; color: white;
    }
    .icon-gold { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .icon-cyan { background: linear-gradient(135deg, #06b6d4, #0891b2); }
    .icon-purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .card-title { font-family: 'Rajdhani', sans-serif; font-size: 1.1rem; font-weight: 700; color: #FFFFFF; margin: 0; }
    .form-card-body { padding: 1.5rem; }

    /* Form Controls */
    .form-group { margin-bottom: 1.25rem; }
    .form-label { display: block; color: #94a3b8; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; }
    .form-label .required { color: #ef4444; }
    .form-input, .form-select, .form-textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 0.75rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(245, 158, 11, 0.2);
        border-radius: 10px;
        color: #FFFFFF;
        font-size: 0.9rem;
        font-family: inherit;
        transition: all 0.3s ease;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.25em 1.25em;
        padding-right: 2.5rem;
        cursor: pointer;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #f59e0b;
        box-shadow: 0 0 15px rgba(245, 158, 11, 0.2);
        background: rgba(0, 0, 0, 0.4);
    }
    .form-input::placeholder, .form-textarea::placeholder { color: #64748b; }
    .form-select option { background: #0d1b2a; color: #FFFFFF; padding: 0.5rem; }
    .form-hint { color: #64748b; font-size: 0.8rem; margin-top: 0.35rem; }
    .form-textarea { min-height: 100px; resize: vertical; }

    /* Checkbox */
    .checkbox-group { background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(245, 158, 11, 0.1); border-radius: 12px; padding: 1rem; }
    .checkbox-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; border-radius: 8px; transition: all 0.3s ease; cursor: pointer; }
    .checkbox-item:hover { background: rgba(245, 158, 11, 0.1); }
    .checkbox-input { width: 20px; height: 20px; accent-color: #f59e0b; cursor: pointer; }
    .checkbox-label { color: #e2e8f0; font-size: 0.9rem; cursor: pointer; }
    .checkbox-label strong { color: #f59e0b; }

    /* Weight Input */
    .weight-input-group { display: flex; align-items: center; gap: 0.5rem; }
    .weight-input {
        width: 100%;
        box-sizing: border-box;
        padding: 0.75rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(245, 158, 11, 0.2);
        border-radius: 10px;
        color: #f59e0b;
        font-size: 0.9rem;
        font-weight: 600;
        font-family: inherit;
        transition: all 0.3s ease;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: textfield;
    }
    .weight-input::-webkit-outer-spin-button,
    .weight-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .weight-input:focus { outline: none; border-color: #f59e0b; box-shadow: 0 0 15px rgba(245, 158, 11, 0.2); background: rgba(0, 0, 0, 0.4); }

    /* Info Box */
    .info-box {
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: #00E5FF;
        font-size: 0.875rem;
    }
    .info-box i { margin-top: 2px; }
    .info-box strong { color: #FFFFFF; }

    /* Error Alert */
    .error-alert {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        color: #ef4444;
    }
    .error-alert ul { margin: 0; padding-left: 1.25rem; }
    .error-alert li { margin-bottom: 0.25rem; }

    /* Submit Section */
    .submit-section {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(245, 158, 11, 0.15);
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
    }

    @media (max-width: 768px) {
        .create-hero { padding: 1.25rem; }
        .hero-content { flex-direction: column; align-items: flex-start !important; gap: 1rem; }
        .btn-neon { width: 100%; justify-content: center; }
        .form-card-body { padding: 1rem; }
    }
</style>
@endpush

@section('content')
<div class="honor-create-container">
    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Hero Section -->
        <div class="create-hero">
            <div class="hero-content flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="hero-icon"><i class="fas fa-plus"></i></div>
                    <div>
                        <h1 class="hero-title">{{ __('app.honor.create_event') }}</h1>
                        <p class="hero-subtitle">Tạo sự kiện vinh danh và bình chọn mới</p>
                    </div>
                </div>
                <a href="{{ route('admin.honor.index') }}" class="btn-neon">
                    <i class="fas fa-arrow-left"></i><span>{{ __('app.common.back') }}</span>
                </a>
            </div>
        </div>

        <!-- Error Alert -->
        @if($errors->any())
        <div class="error-alert">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.honor.store') }}" method="POST">
            @csrf

            <!-- Basic Info Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon icon-gold"><i class="fas fa-info-circle"></i></div>
                    <h3 class="card-title">Thông tin cơ bản</h3>
                </div>
                <div class="form-card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">{{ __('app.honor.col_event_name') }} <span class="required">*</span></label>
                            <input type="text" name="name" class="form-input" value="{{ old('name') }}" required placeholder="Nhập tên sự kiện">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('app.honor.col_target') }} <span class="required">*</span></label>
                            <select name="target_type" class="form-select" required>
                                <option value="">{{ __('app.common.select') }}</option>
                                <option value="user" {{ old('target_type') === 'user' ? 'selected' : '' }}>User</option>
                                <option value="team" {{ old('target_type') === 'team' ? 'selected' : '' }}>Team</option>
                                <option value="tournament" {{ old('target_type') === 'tournament' ? 'selected' : '' }}>Tournament</option>
                                <option value="game" {{ old('target_type') === 'game' ? 'selected' : '' }}>Game</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('app.common.description') }}</label>
                        <textarea name="description" class="form-textarea" placeholder="Mô tả sự kiện...">{{ old('description') }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">{{ __('app.honor.col_mode') }} <span class="required">*</span></label>
                            <select name="mode" class="form-select" required>
                                <option value="free" {{ old('mode') === 'free' ? 'selected' : '' }}>{{ __('app.honor.mode_free') }} (Free mode)</option>
                                <option value="event" {{ old('mode') === 'event' ? 'selected' : '' }}>{{ __('app.honor.mode_event') }} (Event mode)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('app.common.allow_anonymous') }}</label>
                            <label class="checkbox-item" style="margin-top: 0.5rem;">
                                <input type="checkbox" name="allow_anonymous" value="1" class="checkbox-input" {{ old('allow_anonymous') ? 'checked' : '' }}>
                                <span class="checkbox-label">{{ __('app.common.allow_anonymous') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Settings Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon icon-cyan"><i class="fas fa-clock"></i></div>
                    <h3 class="card-title">Thời gian</h3>
                </div>
                <div class="form-card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">{{ __('app.honor.time_from') }}</label>
                            <input type="datetime-local" name="start_time" id="start_time" class="form-input" value="{{ old('start_time') }}">
                            <p class="form-hint">{{ __('app.common.leave_blank_immediate') }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('app.honor.time_to') }}</label>
                            <input type="datetime-local" name="end_time" id="end_time" class="form-input" value="{{ old('end_time') }}">
                            <p class="form-hint">{{ __('app.honor.time_unlimited') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon icon-purple"><i class="fas fa-user-shield"></i></div>
                    <h3 class="card-title">{{ __('app.common.permissions_by_role') }}</h3>
                </div>
                <div class="form-card-body">
                    <div class="checkbox-group">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <label class="checkbox-item">
                                <input type="checkbox" name="allow_participant_vote" value="1" class="checkbox-input" {{ old('allow_participant_vote', true) ? 'checked' : '' }}>
                                <span class="checkbox-label"><strong>Participant</strong> - Người tham gia</span>
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="allow_admin_vote" value="1" class="checkbox-input" {{ old('allow_admin_vote', true) ? 'checked' : '' }}>
                                <span class="checkbox-label"><strong>Admin</strong> - {{ __('app.profile.manage_users') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weights Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon icon-gold"><i class="fas fa-balance-scale"></i></div>
                    <h3 class="card-title">{{ __('app.common.weights_by_role') }}</h3>
                </div>
                <div class="form-card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Participant weight <span class="required">*</span></label>
                            <input type="number" name="participant_weight" class="weight-input" value="{{ old('participant_weight', 1.0) }}" step="0.1" min="0.1" max="10" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Admin weight <span class="required">*</span></label>
                            <input type="number" name="admin_weight" class="weight-input" value="{{ old('admin_weight', 2.0) }}" step="0.1" min="0.1" max="10" required>
                        </div>
                    </div>
                    <div class="info-box" style="margin-top: 1rem;">
                        <i class="fas fa-info-circle"></i>
                        <span><strong>{{ __('app.common.note') }}</strong> {{ __('app.common.weight_note') }}</span>
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="submit-section">
                <div class="flex justify-end gap-3 flex-wrap">
                    <a href="{{ route('admin.honor.index') }}" class="btn-neon">
                        <i class="fas fa-times"></i><span>{{ __('app.common.cancel') }}</span>
                    </a>
                    <button type="submit" class="btn-neon btn-neon-gold">
                        <i class="fas fa-save"></i><span>{{ __('app.common.create') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    startTimeInput.min = now.toISOString().slice(0, 16);
    
    startTimeInput.addEventListener('change', function() {
        if (this.value) {
            endTimeInput.min = this.value;
        }
    });
});
</script>
@endpush
