@extends('layouts.app')

@section('title', 'Đổi mật khẩu')

@push('styles')
<style>
    .password-container {
        background: #000814;
        min-height: 100vh;
        padding: 1.5rem;
    }

    /* Hero Section */
    .password-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .password-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #00E5FF, #8b5cf6, transparent);
    }
    .hero-icon {
        width: 60px; height: 60px; min-width: 60px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 0 25px rgba(99, 102, 241, 0.3);
    }
    .hero-icon i { font-size: 1.5rem; color: white; }
    .hero-title { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; color: #00E5FF; margin: 0; }
    .hero-subtitle { color: #94a3b8; font-size: 0.9rem; margin: 0.25rem 0 0 0; }

    /* Form Card */
    .form-card {
        background: linear-gradient(145deg, #0d1b2a, #000022);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .form-card-header {
        background: linear-gradient(135deg, rgba(0, 0, 85, 0.5), rgba(0, 0, 34, 0.5));
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .form-card-header i { color: #00E5FF; font-size: 1.1rem; }
    .form-card-header h3 { font-family: 'Rajdhani', sans-serif; font-size: 1.1rem; font-weight: 600; color: #FFFFFF; margin: 0; }
    .form-card-body { padding: 1.5rem; }

    /* Form Elements */
    .form-group { margin-bottom: 1.25rem; }
    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: #94a3b8;
        margin-bottom: 0.5rem;
    }
    .form-label i { color: #00E5FF; }
    .input-group {
        position: relative;
        display: flex;
        align-items: center;
    }
    .form-input {
        width: 100%;
        box-sizing: border-box;
        background: rgba(0, 0, 20, 0.6);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        padding: 0.75rem 3rem 0.75rem 1rem;
        color: #FFFFFF;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .form-input:focus {
        outline: none;
        border-color: #00E5FF;
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.2);
        background: rgba(0, 0, 40, 0.6);
    }
    .form-input::placeholder { color: #64748b; }
    .toggle-password {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 0.5rem;
        transition: color 0.3s ease;
    }
    .toggle-password:hover { color: #00E5FF; }

    /* Password Strength */
    .strength-bar {
        height: 6px;
        background: rgba(0, 0, 20, 0.6);
        border-radius: 3px;
        margin-top: 0.75rem;
        overflow: hidden;
    }
    .strength-fill {
        height: 100%;
        border-radius: 3px;
        transition: all 0.3s ease;
        width: 0%;
    }
    .strength-weak { background: linear-gradient(90deg, #ef4444, #f87171); }
    .strength-medium { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .strength-strong { background: linear-gradient(90deg, #10b981, #34d399); }
    .strength-text {
        font-size: 0.75rem;
        margin-top: 0.5rem;
        color: #94a3b8;
    }

    /* Match Indicator */
    .match-indicator {
        font-size: 0.8rem;
        margin-top: 0.5rem;
        padding: 0.5rem;
        border-radius: 6px;
        display: none;
    }
    .match-success {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        display: block;
    }
    .match-error {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
        display: block;
    }

    /* Buttons */
    .btn-neon {
        background: linear-gradient(135deg, #000055, #000077);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.4);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    .btn-neon:hover {
        background: rgba(0, 229, 255, 0.15);
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.4);
        color: #FFFFFF;
        transform: translateY(-2px);
    }
    .btn-neon-primary {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-color: rgba(139, 92, 246, 0.4);
        color: #FFFFFF;
    }
    .btn-neon-primary:hover { box-shadow: 0 0 20px rgba(139, 92, 246, 0.4); }
    .btn-neon-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    /* Alert */
    .alert-error {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
    }
    .alert-info {
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.2);
        color: #00E5FF;
        padding: 1rem;
        border-radius: 10px;
        margin-top: 1rem;
    }
    .alert-info ul {
        margin: 0.5rem 0 0 1.25rem;
        padding: 0;
        color: #94a3b8;
    }
    .alert-info li { margin-bottom: 0.25rem; }
</style>
@endpush

@section('content')
<div class="password-container">
    <div class="max-w-lg mx-auto">
        <!-- Hero Section -->
        <div class="password-hero">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="hero-icon">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div>
                        <h1 class="hero-title">Đổi mật khẩu</h1>
                        <p class="hero-subtitle">Cập nhật mật khẩu bảo mật tài khoản</p>
                    </div>
                </div>
                <a href="{{ route('profile.show', auth()->user()) }}" class="btn-neon">
                    <i class="fa-solid fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>
        </div>

        @if($errors->any())
        <div class="alert-error">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <strong>Có lỗi xảy ra:</strong>
            <ul class="mt-2 ml-4 list-disc">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('profile.update-password') }}" id="passwordForm">
            @csrf
            @method('PUT')

            <div class="form-card">
                <div class="form-card-header">
                    <i class="fa-solid fa-lock"></i>
                    <h3>Thông tin mật khẩu</h3>
                </div>
                <div class="form-card-body">
                    <!-- Current Password -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-lock"></i>
                            Mật khẩu hiện tại <span class="text-red-400">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="current_password" 
                                class="form-input" placeholder="Nhập mật khẩu hiện tại" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                                <i class="fa-solid fa-eye" id="current_password_icon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-key"></i>
                            Mật khẩu mới <span class="text-red-400">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" 
                                class="form-input" placeholder="Nhập mật khẩu mới" required minlength="8">
                            <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                <i class="fa-solid fa-eye" id="password_icon"></i>
                            </button>
                        </div>
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <p class="strength-text" id="strengthText"></p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-check-double"></i>
                            Xác nhận mật khẩu mới <span class="text-red-400">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                class="form-input" placeholder="Nhập lại mật khẩu mới" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                                <i class="fa-solid fa-eye" id="password_confirmation_icon"></i>
                            </button>
                        </div>
                        <div class="match-indicator" id="matchIndicator"></div>
                    </div>

                    <!-- Info -->
                    <div class="alert-info">
                        <i class="fa-solid fa-shield-halved"></i>
                        <strong>Yêu cầu mật khẩu:</strong>
                        <ul>
                            <li>Ít nhất 8 ký tự</li>
                            <li>Nên có chữ hoa và chữ thường</li>
                            <li>Nên có số và ký tự đặc biệt</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-between">
                <button type="submit" class="btn-neon btn-neon-primary" id="submitBtn" disabled>
                    <i class="fa-solid fa-key"></i>
                    Đổi mật khẩu
                </button>
                <a href="{{ route('profile.show', auth()->user()) }}" class="btn-neon">
                    <i class="fa-solid fa-xmark"></i>
                    Hủy bỏ
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentPassword = document.getElementById('current_password');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    const submitBtn = document.getElementById('submitBtn');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    const matchIndicator = document.getElementById('matchIndicator');

    // Password strength checker
    password.addEventListener('input', function() {
        const value = this.value;
        const strength = calculateStrength(value);
        
        strengthFill.style.width = strength.percent + '%';
        strengthFill.className = 'strength-fill ' + strength.class;
        strengthText.textContent = strength.text;
        
        checkFormValidity();
    });

    // Password match checker
    confirmPassword.addEventListener('input', function() {
        const pwd = password.value;
        const confirm = this.value;
        
        if (confirm) {
            if (pwd === confirm) {
                matchIndicator.textContent = '✓ Mật khẩu khớp';
                matchIndicator.className = 'match-indicator match-success';
            } else {
                matchIndicator.textContent = '✗ Mật khẩu không khớp';
                matchIndicator.className = 'match-indicator match-error';
            }
        } else {
            matchIndicator.className = 'match-indicator';
        }
        
        checkFormValidity();
    });

    currentPassword.addEventListener('input', checkFormValidity);

    function calculateStrength(pwd) {
        let score = 0;
        if (pwd.length >= 8) score += 25;
        if (/[a-z]/.test(pwd)) score += 20;
        if (/[A-Z]/.test(pwd)) score += 20;
        if (/[0-9]/.test(pwd)) score += 15;
        if (/[^A-Za-z0-9]/.test(pwd)) score += 20;

        if (pwd.length === 0) return { percent: 0, class: '', text: '' };
        if (score < 40) return { percent: score, class: 'strength-weak', text: '🔴 Yếu' };
        if (score < 70) return { percent: score, class: 'strength-medium', text: '🟡 Trung bình' };
        return { percent: Math.min(score, 100), class: 'strength-strong', text: '🟢 Mạnh' };
    }

    function checkFormValidity() {
        const isValid = currentPassword.value.length > 0 &&
            password.value.length >= 8 &&
            password.value === confirmPassword.value;
        
        submitBtn.disabled = !isValid;
    }
});

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '_icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
