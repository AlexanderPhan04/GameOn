@extends('layouts.app')

@section('title', __('app.auth.reset_password') . ' - ' . __('app.name'))

@push('styles')
<style>
    /* Override body background for auth page - Deep Blue Design System */
    body {
        background: #000814 !important;
        color: #FFFFFF !important;
        padding-top: 90px !important;
    }
    
    .content-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 90px);
        padding: 2rem 0;
    }

    .reset-password-container {
        background: #0d1b2a;
        backdrop-filter: blur(25px);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 30px;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.6), 0 0 30px rgba(0, 229, 255, 0.1);
        position: relative;
        overflow: hidden;
        width: 480px;
        max-width: 95%;
        font-family: 'Inter', sans-serif;
    }

    .reset-password-header {
        background: linear-gradient(135deg, #000022 0%, #000055 50%, #000022 100%);
        padding: 40px 30px 30px;
        text-align: center;
        position: relative;
    }
    
    .reset-password-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(0, 229, 255, 0.5), transparent);
    }
    
    .header-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #000055 0%, #000077 100%);
        border: 2px solid rgba(0, 229, 255, 0.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 0 25px rgba(0, 229, 255, 0.3);
    }
    
    .header-icon i {
        font-size: 28px;
        color: #00E5FF;
        text-shadow: 0 0 15px rgba(0, 229, 255, 0.5);
    }

    .reset-password-header h3 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 28px;
        font-weight: 700;
        color: #00E5FF;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
        text-shadow: 0 0 20px rgba(0, 229, 255, 0.5);
    }

    .reset-password-header p {
        color: #94a3b8;
        font-size: 14px;
        margin: 0;
        line-height: 1.6;
    }

    .reset-password-body {
        padding: 30px 40px 40px;
    }

    .email-badge {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 25px;
    }
    
    .email-badge i {
        color: #00E5FF;
        font-size: 18px;
    }
    
    .email-badge span {
        color: #00E5FF;
        font-size: 14px;
        font-family: 'Monaco', 'Consolas', monospace;
    }

    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #FFFFFF;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 8px;
        font-family: 'Inter', sans-serif;
    }
    
    .form-label i {
        color: #00E5FF;
        font-size: 14px;
    }

    .form-control {
        background-color: rgba(0, 229, 255, 0.05);
        border: 1px solid rgba(0, 229, 255, 0.2);
        color: #FFFFFF;
        padding: 14px 16px;
        font-size: 14px;
        border-radius: 10px;
        width: 100%;
        box-sizing: border-box;
        outline: none;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
    }
    
    .form-control::placeholder {
        color: #94a3b8;
    }

    .form-control:focus {
        background-color: rgba(0, 229, 255, 0.1);
        border-color: #00E5FF;
        box-shadow: 0 0 0 3px rgba(0, 229, 255, 0.15), 0 0 15px rgba(0, 229, 255, 0.2);
    }
    
    .password-wrapper {
        position: relative;
    }
    
    .password-wrapper input {
        padding-right: 45px !important;
    }
    
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 0;
        transition: color 0.3s ease;
    }
    
    .password-toggle:hover {
        color: #00E5FF;
    }

    /* Password Strength */
    .password-strength {
        margin-top: 10px;
    }
    
    .strength-bar-container {
        height: 4px;
        background: rgba(0, 229, 255, 0.1);
        border-radius: 2px;
        overflow: hidden;
    }
    
    .strength-bar {
        height: 100%;
        border-radius: 2px;
        transition: all 0.3s ease;
        width: 0%;
    }
    
    .strength-text {
        font-size: 11px;
        margin-top: 5px;
        color: #94a3b8;
    }

    /* Requirements */
    .requirements {
        margin-top: 12px;
        padding: 12px;
        background: rgba(0, 0, 34, 0.5);
        border-radius: 8px;
        border: 1px solid rgba(26, 35, 126, 0.3);
    }
    
    .requirements-title {
        font-size: 11px;
        color: #94a3b8;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .requirements ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
    }
    
    .requirements li {
        font-size: 11px;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
    }
    
    .requirements li.valid {
        color: #10b981;
    }
    
    .requirements li.invalid {
        color: #64748b;
    }
    
    .requirements li i {
        font-size: 10px;
    }

    .form-text {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        font-size: 12px;
    }
    
    .form-text.text-success {
        color: #10b981;
    }
    
    .form-text.text-danger {
        color: #ef4444;
    }

    .btn-submit {
        background: linear-gradient(135deg, #000055 0%, #000077 100%) !important;
        color: #FFFFFF !important;
        font-size: 14px;
        padding: 14px 30px;
        border: 1px solid rgba(0, 229, 255, 0.4) !important;
        border-radius: 10px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 229, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-family: 'Rajdhani', sans-serif;
    }

    .btn-submit:hover:not(:disabled) {
        background: linear-gradient(135deg, #000077 0%, #0000aa 100%) !important;
        border-color: #00E5FF !important;
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.5), 0 6px 15px rgba(0, 229, 255, 0.3);
        transform: translateY(-2px);
    }
    
    .btn-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.15em;
    }

    .back-to-login {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #94a3b8;
        font-size: 14px;
        text-decoration: none;
        margin-top: 20px;
        transition: all 0.3s ease;
    }

    .back-to-login:hover {
        color: #00E5FF;
    }
    
    .back-to-login i {
        font-size: 12px;
    }

    /* Custom Alert Styles */
    .alert {
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 20px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .alert-success {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.4);
        color: #10b981;
    }
    
    .alert-success::before {
        content: '\f058';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        font-size: 16px;
    }
    
    .alert-danger {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: #ef4444;
    }
    
    .alert-danger::before {
        content: '\f057';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        font-size: 16px;
    }
    
    .alert .btn-close {
        filter: invert(1);
        opacity: 0.6;
        margin-left: auto;
        padding: 0;
        background-size: 10px;
    }
    
    .alert .btn-close:hover {
        opacity: 1;
    }

    @media (max-width: 576px) {
        .reset-password-container {
            border-radius: 20px;
            margin: 0 15px;
        }
        
        .reset-password-header {
            padding: 30px 20px 25px;
        }
        
        .reset-password-body {
            padding: 25px 20px 30px;
        }
        
        .reset-password-header h3 {
            font-size: 24px;
        }
        
        .requirements ul {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="reset-password-container">
    <div class="reset-password-header">
        <div class="header-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h3>{{ __('app.auth.reset_password') }}</h3>
        <p>{{ __('app.auth.create_new_password') }}</p>
    </div>

    <div class="reset-password-body">
        <div id="alert-container"></div>

        <div class="email-badge">
            <i class="fas fa-user"></i>
            <span>{{ $email }}</span>
        </div>

        <form id="resetPasswordForm" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-key"></i>
                    <span>{{ __('app.auth.new_password') }}</span>
                </label>
                <div class="password-wrapper">
                    <input type="password" class="form-control" id="password" name="password" required
                        placeholder="{{ __('app.auth.enter_new_password') }}">
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>

                <!-- Password strength indicator -->
                <div class="password-strength">
                    <div class="strength-bar-container">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    <div class="strength-text" id="strengthText"></div>
                </div>

                <!-- Password requirements -->
                <div class="requirements">
                    <div class="requirements-title">{{ __('app.auth.password_requirements') }}</div>
                    <ul>
                        <li id="req-length" class="invalid">
                            <i class="fas fa-times"></i> {{ __('app.auth.min_8_chars') }}
                        </li>
                        <li id="req-uppercase" class="invalid">
                            <i class="fas fa-times"></i> {{ __('app.auth.one_uppercase') }}
                        </li>
                        <li id="req-lowercase" class="invalid">
                            <i class="fas fa-times"></i> {{ __('app.auth.one_lowercase') }}
                        </li>
                        <li id="req-number" class="invalid">
                            <i class="fas fa-times"></i> {{ __('app.auth.one_number') }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">
                    <i class="fas fa-check-double"></i>
                    <span>{{ __('app.auth.confirm_password') }}</span>
                </label>
                <div class="password-wrapper">
                    <input type="password" class="form-control" id="password_confirmation"
                        name="password_confirmation" required
                        placeholder="{{ __('app.auth.enter_confirm_password') }}">
                    <button type="button" class="password-toggle" id="toggleConfirmPassword">
                        <i class="fas fa-eye" id="eyeIconConfirm"></i>
                    </button>
                </div>
                <div class="form-text" id="confirmationText"></div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn" disabled>
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                <i class="fas fa-save"></i>
                <span>{{ __('app.auth.reset_password') }}</span>
            </button>
        </form>

        <a href="{{ route('auth.login') }}" class="back-to-login">
            <i class="fas fa-arrow-left"></i>
            <span>{{ __('app.auth.back_to_login') }}</span>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('resetPasswordForm');
    const submitBtn = document.getElementById('submitBtn');
    const spinner = submitBtn.querySelector('.spinner-border');
    const alertContainer = document.getElementById('alert-container');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeIconConfirm = document.getElementById('eyeIconConfirm');

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Toggle password visibility
    togglePassword.addEventListener('click', function(e) {
        e.preventDefault();
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        eyeIcon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    });
    
    toggleConfirmPassword.addEventListener('click', function(e) {
        e.preventDefault();
        const type = confirmInput.type === 'password' ? 'text' : 'password';
        confirmInput.type = type;
        eyeIconConfirm.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    });

    // Password strength checking
    passwordInput.addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkFormValidity();
    });

    confirmInput.addEventListener('input', function() {
        checkPasswordMatch();
        checkFormValidity();
    });

    function checkPasswordStrength(password) {
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /\d/.test(password)
        };

        // Update requirement indicators
        updateRequirement('req-length', requirements.length);
        updateRequirement('req-uppercase', requirements.uppercase);
        updateRequirement('req-lowercase', requirements.lowercase);
        updateRequirement('req-number', requirements.number);

        // Calculate strength
        const score = Object.values(requirements).filter(Boolean).length;
        updateStrengthBar(score);

        return Object.values(requirements).every(Boolean);
    }

    function updateRequirement(id, isValid) {
        const element = document.getElementById(id);
        if (isValid) {
            element.className = 'valid';
            element.querySelector('i').className = 'fas fa-check';
        } else {
            element.className = 'invalid';
            element.querySelector('i').className = 'fas fa-times';
        }
    }

    function updateStrengthBar(score) {
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');

        const colors = ['#ef4444', '#f59e0b', '#eab308', '#10b981'];
        const texts = ['{{ __("app.auth.very_weak") }}', '{{ __("app.auth.weak") }}', '{{ __("app.auth.medium") }}', '{{ __("app.auth.strong") }}'];

        if (score === 0) {
            strengthBar.style.width = '0%';
            strengthBar.style.backgroundColor = 'transparent';
            strengthText.textContent = '';
        } else {
            strengthBar.style.width = (score * 25) + '%';
            strengthBar.style.backgroundColor = colors[score - 1];
            strengthText.textContent = texts[score - 1];
            strengthText.style.color = colors[score - 1];
        }
    }

    function checkPasswordMatch() {
        const confirmationText = document.getElementById('confirmationText');
        const password = passwordInput.value;
        const confirm = confirmInput.value;

        if (!confirm) {
            confirmationText.textContent = '';
            confirmationText.className = 'form-text';
        } else if (password === confirm) {
            confirmationText.innerHTML = '<i class="fas fa-check"></i> {{ __("app.auth.passwords_match") }}';
            confirmationText.className = 'form-text text-success';
        } else {
            confirmationText.innerHTML = '<i class="fas fa-times"></i> {{ __("app.auth.passwords_not_match") }}';
            confirmationText.className = 'form-text text-danger';
        }
    }

    function checkFormValidity() {
        const password = passwordInput.value;
        const confirm = confirmInput.value;
        const isPasswordStrong = checkPasswordStrength(password);
        const isPasswordMatch = password === confirm && confirm.length > 0;

        submitBtn.disabled = !(isPasswordStrong && isPasswordMatch);
    }

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Show loading state
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');

        // Clear previous alerts
        alertContainer.innerHTML = '';

        const formData = new FormData(form);

        try {
            const response = await fetch('{{ route("auth.reset.password.process") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });

            const data = await response.json();

            if (data.success) {
                showAlert(data.message, 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("auth.login") }}';
                }, 2000);
            } else {
                showAlert(data.message || '{{ __("app.auth.error_occurred") }}', 'danger');
                submitBtn.disabled = false;
            }

        } catch (error) {
            console.error('Reset password error:', error);
            showAlert('{{ __("app.auth.connection_error") }}', 'danger');
            submitBtn.disabled = false;
        } finally {
            // Hide loading state
            spinner.classList.add('d-none');
        }
    });

    function showAlert(message, type) {
        alertContainer.innerHTML = '';
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            <span>${message}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        alertContainer.appendChild(alert);
    }
});
</script>
@endpush
