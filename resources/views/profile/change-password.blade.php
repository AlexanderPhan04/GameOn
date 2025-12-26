@extends('layouts.app')

@section('title', 'Đổi mật khẩu')

@push('styles')
<style>
    /* Modern Password Change Page Styles */
    .password-change-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        background-attachment: fixed;
        padding: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .password-change-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="%23ffffff05" points="0,1000 1000,0 1000,1000"/></svg>');
        pointer-events: none;
    }

    .modern-password-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        position: relative;
        animation: slideInUp 0.6s ease-out;
    }

    .modern-password-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
        background-size: 300% 100%;
        animation: gradientShift 3s ease infinite;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .modern-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        position: relative;
        border: none;
    }

    .modern-card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }

    .modern-card-header .content {
        position: relative;
        z-index: 1;
    }

    .modern-card-header h5 {
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .modern-back-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
    }

    .modern-back-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .modern-card-body {
        padding: 2.5rem;
        background: rgba(255, 255, 255, 0.98);
    }

    .modern-form-group {
        margin-bottom: 2rem;
        position: relative;
    }

    .modern-form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        letter-spacing: 0.025em;
        display: flex;
        align-items: center;
    }

    .modern-form-label i {
        width: 20px;
        text-align: center;
    }

    .modern-input-group {
        position: relative;
        background: white;
        border-radius: 16px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .modern-input-group:focus-within {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .modern-form-control {
        border: none;
        padding: 1rem 1.25rem;
        font-size: 1rem;
        background: transparent;
        color: #2d3748;
        transition: all 0.3s ease;
    }

    .modern-form-control:focus {
        box-shadow: none;
        outline: none;
        background: transparent;
    }

    .modern-toggle-btn {
        border: none;
        background: transparent;
        color: #718096;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .modern-toggle-btn:hover {
        color: #667eea;
        background: rgba(102, 126, 234, 0.1);
    }

    .modern-alert {
        border-radius: 16px;
        border: none;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .modern-alert-danger {
        background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
        color: #c53030;
    }

    .modern-alert-info {
        background: linear-gradient(135deg, #bee3f8 0%, #90cdf4 100%);
        color: #2b6cb0;
        border-left: 4px solid #3182ce;
    }

    .password-strength-container {
        margin-top: 0.75rem;
    }

    .modern-progress {
        height: 8px;
        background: #f0f0f0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .modern-progress-bar {
        height: 100%;
        border-radius: 10px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .modern-progress-bar::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    .strength-very-weak { background: linear-gradient(135deg, #fc8181, #f56565); }
    .strength-weak { background: linear-gradient(135deg, #f6ad55, #ed8936); }
    .strength-medium { background: linear-gradient(135deg, #68d391, #48bb78); }
    .strength-strong { background: linear-gradient(135deg, #4fd1c7, #38b2ac); }

    .password-match-indicator {
        margin-top: 0.5rem;
        padding: 0.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .match-success {
        background: rgba(72, 187, 120, 0.1);
        color: #38a169;
    }

    .match-error {
        background: rgba(245, 101, 101, 0.1);
        color: #e53e3e;
    }

    .modern-btn-group {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }

    .modern-btn {
        padding: 0.875rem 2rem;
        border-radius: 16px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .modern-btn-secondary {
        background: #f7fafc;
        color: #4a5568;
        border-color: #e2e8f0;
    }

    .modern-btn-secondary:hover {
        background: #edf2f7;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .modern-btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .modern-btn-primary:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    .modern-btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    }

    .modern-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .modern-btn:hover::before {
        left: 100%;
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .password-change-container {
            padding: 1rem;
        }

        .modern-password-card {
            margin: 0 0.5rem;
            border-radius: 20px;
        }

        .modern-card-header,
        .modern-card-body {
            padding: 1.5rem;
        }

        .modern-btn-group {
            flex-direction: column;
        }

        .modern-btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* Animation for form validation */
    .shake {
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    /* Focus ring improvements */
    .modern-input-group:focus-within .modern-form-control {
        color: #2d3748;
    }

    /* Loading state */
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="password-change-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7 col-sm-9">
                <div class="modern-password-card">
                    <div class="modern-card-header">
                        <div class="content">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-key me-3"></i>Đổi mật khẩu
                                </h5>
                                <a href="{{ route('profile.show') }}" class="modern-back-btn text-decoration-none">
                                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modern-card-body">
                        @if($errors->any())
                        <div class="modern-alert modern-alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Có lỗi xảy ra:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update-password') }}" id="passwordForm">
                            @csrf
                            @method('PUT')

                            <!-- Current Password -->
                            <div class="modern-form-group">
                                <label for="current_password" class="modern-form-label">
                                    <i class="fas fa-lock text-secondary me-2"></i>
                                    Mật khẩu hiện tại <span class="text-danger">*</span>
                                </label>
                                <div class="modern-input-group">
                                    <input type="password"
                                        class="modern-form-control form-control @error('current_password') is-invalid @enderror"
                                        id="current_password"
                                        name="current_password"
                                        placeholder="Nhập mật khẩu hiện tại"
                                        required>
                                    <button class="modern-toggle-btn" type="button" onclick="togglePassword('current_password')">
                                        <i class="fas fa-eye" id="current_password_icon"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div class="modern-form-group">
                                <label for="password" class="modern-form-label">
                                    <i class="fas fa-key text-primary me-2"></i>
                                    Mật khẩu mới <span class="text-danger">*</span>
                                </label>
                                <div class="modern-input-group">
                                    <input type="password"
                                        class="modern-form-control form-control @error('password') is-invalid @enderror"
                                        id="password"
                                        name="password"
                                        placeholder="Nhập mật khẩu mới"
                                        required
                                        minlength="8">
                                    <button class="modern-toggle-btn" type="button" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="password_icon"></i>
                                    </button>
                                </div>
                                @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <!-- Modern Password Strength Indicator -->
                                <div class="password-strength-container">
                                    <div class="modern-progress">
                                        <div class="modern-progress-bar" id="passwordStrength" style="width: 0%"></div>
                                    </div>
                                    <small id="passwordStrengthText" class="text-muted mt-2 d-block"></small>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="modern-form-group">
                                <label for="password_confirmation" class="modern-form-label">
                                    <i class="fas fa-check-double text-success me-2"></i>
                                    Xác nhận mật khẩu mới <span class="text-danger">*</span>
                                </label>
                                <div class="modern-input-group">
                                    <input type="password"
                                        class="modern-form-control form-control"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        placeholder="Nhập lại mật khẩu mới"
                                        required>
                                    <button class="modern-toggle-btn" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye" id="password_confirmation_icon"></i>
                                    </button>
                                </div>
                                <div id="passwordMatch" class="password-match-indicator" style="display: none;"></div>
                            </div>

                            <!-- Modern Security Info -->
                            <div class="modern-alert modern-alert-info">
                                <i class="fas fa-shield-alt me-2"></i>
                                <strong>Yêu cầu mật khẩu:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Ít nhất 8 ký tự</li>
                                    <li>Nên bao gồm chữ hoa, chữ thường</li>
                                    <li>Nên có ít nhất 1 số và 1 ký tự đặc biệt</li>
                                </ul>
                            </div>

                            <!-- Modern Submit Buttons -->
                            <div class="modern-btn-group">
                                <a href="{{ route('profile.show') }}" class="modern-btn modern-btn-secondary text-decoration-none">
                                    <i class="fas fa-times me-2"></i>Hủy
                                </a>
                                <button type="submit" class="modern-btn modern-btn-primary" id="submitBtn" disabled>
                                    <i class="fas fa-key me-2"></i>Đổi mật khẩu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const currentPasswordInput = document.getElementById('current_password');
        const submitBtn = document.getElementById('submitBtn');
        const passwordStrength = document.getElementById('passwordStrength');
        const passwordStrengthText = document.getElementById('passwordStrengthText');
        const passwordMatch = document.getElementById('passwordMatch');

        // Enhanced password strength checker
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);

            passwordStrength.style.width = strength.percentage + '%';
            passwordStrength.className = 'modern-progress-bar ' + strength.class;
            passwordStrengthText.textContent = strength.text;
            passwordStrengthText.className = 'small ' + strength.textClass;

            // Add shake animation for very weak passwords
            if (strength.percentage < 25 && password.length > 0) {
                passwordStrength.parentElement.parentElement.classList.add('shake');
                setTimeout(() => {
                    passwordStrength.parentElement.parentElement.classList.remove('shake');
                }, 500);
            }

            checkFormValidity();
        });

        // Enhanced password confirmation checker
        confirmPasswordInput.addEventListener('input', function() {
            const password = passwordInput.value;
            const confirmPassword = this.value;

            if (confirmPassword) {
                passwordMatch.style.display = 'block';
                if (password === confirmPassword) {
                    passwordMatch.textContent = '✓ Mật khẩu khớp nhau';
                    passwordMatch.className = 'password-match-indicator match-success';
                } else {
                    passwordMatch.textContent = '✗ Mật khẩu không khớp';
                    passwordMatch.className = 'password-match-indicator match-error';
                }
            } else {
                passwordMatch.style.display = 'none';
            }

            checkFormValidity();
        });

        currentPasswordInput.addEventListener('input', checkFormValidity);

        function calculatePasswordStrength(password) {
            let score = 0;
            let feedback = [];

            // Length check
            if (password.length >= 8) score += 25;
            else if (password.length > 0) feedback.push('ít nhất 8 ký tự');

            // Lowercase check
            if (/[a-z]/.test(password)) score += 20;
            else if (password.length > 0) feedback.push('chữ thường');

            // Uppercase check
            if (/[A-Z]/.test(password)) score += 20;
            else if (password.length > 0) feedback.push('chữ hoa');

            // Number check
            if (/[0-9]/.test(password)) score += 15;
            else if (password.length > 0) feedback.push('số');

            // Special character check
            if (/[^A-Za-z0-9]/.test(password)) score += 20;
            else if (password.length > 0) feedback.push('ký tự đặc biệt');

            let strength = {
                percentage: Math.min(score, 100),
                class: '',
                text: '',
                textClass: ''
            };

            if (password.length === 0) {
                strength.percentage = 0;
                strength.text = '';
                strength.class = '';
            } else if (score < 25) {
                strength.class = 'strength-very-weak';
                strength.text = '🔴 Rất yếu - Cần: ' + feedback.join(', ');
                strength.textClass = 'text-danger fw-medium';
            } else if (score < 50) {
                strength.class = 'strength-weak';
                strength.text = '🟡 Yếu - Cần thêm: ' + feedback.join(', ');
                strength.textClass = 'text-warning fw-medium';
            } else if (score < 80) {
                strength.class = 'strength-medium';
                strength.text = '🟢 Trung bình' + (feedback.length ? ' - Tốt hơn nếu có: ' + feedback.join(', ') : '');
                strength.textClass = 'text-info fw-medium';
            } else {
                strength.class = 'strength-strong';
                strength.text = '💚 Mạnh - Mật khẩu rất tốt!';
                strength.textClass = 'text-success fw-medium';
            }

            return strength;
        }

        function checkFormValidity() {
            const currentPassword = currentPasswordInput.value.trim();
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            const isValid = currentPassword.length > 0 &&
                password.length >= 8 &&
                password === confirmPassword &&
                calculatePasswordStrength(password).percentage >= 25;

            submitBtn.disabled = !isValid;
            
            // Add visual feedback
            if (isValid) {
                submitBtn.style.opacity = '1';
                submitBtn.style.transform = 'translateY(0)';
            } else {
                submitBtn.style.opacity = '0.6';
            }
        }

        // Enhanced form submission with better loading state
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const currentPassword = currentPasswordInput.value.trim();
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            // Final validation
            if (!currentPassword || password.length < 8 || password !== confirmPassword) {
                e.preventDefault();
                
                // Show modern error notification (if you have the notification system)
                if (typeof showModernNotification === 'function') {
                    showModernNotification('Lỗi xác thực', 'Vui lòng kiểm tra lại thông tin đã nhập', 'error');
                }
                
                // Shake the form
                document.querySelector('.modern-password-card').classList.add('shake');
                setTimeout(() => {
                    document.querySelector('.modern-password-card').classList.remove('shake');
                }, 500);
                
                return;
            }

            // Update button to loading state
            submitBtn.innerHTML = '<span class="loading-spinner me-2"></span>Đang đổi mật khẩu...';
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.8';
            
            // Disable all form inputs
            const inputs = this.querySelectorAll('input, button');
            inputs.forEach(input => {
                if (input !== submitBtn) input.disabled = true;
            });
        });

        // Auto-focus first input
        setTimeout(() => {
            currentPasswordInput.focus();
        }, 300);
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

        // Add a subtle animation to the icon
        icon.style.transform = 'scale(0.8)';
        setTimeout(() => {
            icon.style.transform = 'scale(1)';
        }, 150);
    }

    // Add smooth scroll behavior for mobile
    if (window.innerWidth <= 768) {
        document.addEventListener('focusin', function(e) {
            if (e.target.matches('.modern-form-control')) {
                setTimeout(() => {
                    e.target.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }, 300);
            }
        });
    }
</script>
@endsection