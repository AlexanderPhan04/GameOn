@extends('layouts.app')

@section('title', __('app.auth.login') . ' / ' . __('app.auth.register') . ' - ' . __('app.name'))

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    /* Override body background for auth page - using header colors */
    body {
        background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 30%, #16213e 70%, #0f0f23 100%) !important;
        color: white !important;
        padding-top: 90px !important;
    }
    
    /* Prevent scrollbars in input fields */
    input[type="email"],
    input[type="password"],
    input[type="text"] {
        overflow: hidden !important;
        overflow-x: hidden !important;
        overflow-y: hidden !important;
    }
    
    .content-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 90px);
        padding: 2rem 0;
    }

    .auth-container {
        background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 30%, #16213e 70%, #0f0f23 100%);
        backdrop-filter: blur(25px);
        border: 1px solid rgba(102, 126, 234, 0.2);
        border-radius: 30px;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.05);
        position: relative;
        overflow: visible;
        width: 900px;
        max-width: 95%;
        min-height: 600px;
        font-family: 'Montserrat', sans-serif;
    }
    
    .auth-container #login-alert-container,
    .auth-container #register-alert-container {
        width: 100%;
        margin-bottom: 10px;
        flex-shrink: 0;
    }
    
    .auth-container #login-alert-container .alert,
    .auth-container #register-alert-container .alert {
        margin-bottom: 0;
        word-wrap: break-word;
    }

    .auth-container p {
        font-size: 14px;
        line-height: 20px;
        letter-spacing: 0.3px;
        margin: 20px 0;
        color: rgba(255, 255, 255, 0.7);
    }

    .auth-container span {
        font-size: 12px;
        width: 100%;
        text-align: center;
        margin: 10px 0;
        display: block;
        color: rgba(255, 255, 255, 0.7);
    }

    .auth-container a {
        color: rgba(255, 255, 255, 0.85);
        font-size: 13px;
        text-decoration: none;
        margin: 10px 0;
        display: block;
        width: 100%;
        box-sizing: border-box;
        transition: color 0.3s ease;
    }

    .auth-container a:hover {
        color: white;
    }

    .auth-container button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: #fff !important;
        font-size: 12px;
        padding: 12px 45px;
        border: 1px solid transparent !important;
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-top: 15px;
        margin-bottom: 0;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    /* Nút submit trong form - nhỏ hơn, chỉ to hơn input một chút, căn giữa */
    .auth-container form button[type="submit"] {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: #fff !important;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        margin-top: 20px !important;
        margin-bottom: 30px !important;
        margin-left: auto !important;
        margin-right: auto !important;
        width: auto !important;
        min-width: 200px;
        max-width: 90%;
        padding: 10px 35px !important;
    }

    .auth-container button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .auth-container button.hidden,
    .auth-container button.toggle-button {
        background-color: transparent !important;
        border: 2px solid #fff !important;
        box-shadow: none !important;
        color: #fff !important;
        margin-top: 20px !important;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    .auth-container button.hidden:hover,
    .auth-container button.toggle-button:hover {
        background-color: rgba(255, 255, 255, 0.2) !important;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2) !important;
        transform: translateY(-2px) !important;
    }

    .auth-container form {
        background: transparent;
        display: flex;
        align-items: flex-start;
        justify-content: flex-start;
        flex-direction: column;
        padding: 40px 50px 100px 50px;
        min-height: 100%;
        height: auto;
        overflow-y: auto;
        overflow-x: hidden;
        box-sizing: border-box;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE and Edge */
        flex: 1;
    }
    
    .auth-container form::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }

    .auth-container form h1 {
        margin-bottom: 20px;
        font-size: 28px;
        font-weight: 600;
        color: white;
        width: 100%;
        text-align: center;
    }

    .auth-container input {
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(102, 126, 234, 0.2);
        color: white;
        margin: 8px 0 15px 0;
        padding: 12px 15px;
        font-size: 13px;
        border-radius: 8px;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        outline: none;
        transition: all 0.3s ease;
        overflow: hidden;
        resize: none;
        line-height: 1.5;
    }
    
    .auth-container input[type="email"],
    .auth-container input[type="password"],
    .auth-container input[type="text"] {
        overflow-x: hidden;
        overflow-y: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .auth-container input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .auth-container input:focus {
        background-color: rgba(255, 255, 255, 0.15);
        border-color: rgba(102, 126, 234, 0.5);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    }

    .form-container {
        position: absolute;
        top: 0;
        height: 100%;
        transition: all 0.6s ease-in-out;
        overflow: hidden;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
    }
    
    /* Đảm bảo form không che toggle panel */
    .sign-in {
        z-index: 2;
    }
    
    .sign-up {
        z-index: 1;
    }
    
    .auth-container.active .sign-in {
        z-index: 1;
    }
    
    .auth-container.active .sign-up {
        z-index: 2;
    }

    .sign-in {
        left: 0;
        width: 50%;
        z-index: 2;
        opacity: 1;
    }

    .auth-container.active .sign-in {
        transform: translateX(100%);
        opacity: 0;
        z-index: 1;
    }

    .sign-up {
        left: 0;
        width: 50%;
        opacity: 0;
        z-index: 1;
    }

    .auth-container.active .sign-up {
        transform: translateX(100%);
        opacity: 1;
        z-index: 6;
        animation: move 0.6s;
    }

    @keyframes move {
        0%, 49.99% {
            opacity: 0;
            z-index: 1;
        }
        50%, 100% {
            opacity: 1;
            z-index: 5;
        }
    }

    .social-icons {
        margin: 20px 0;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }

    .social-icons a {
        border: 1px solid rgba(102, 126, 234, 0.3);
        background: rgba(255, 255, 255, 0.05);
        border-radius: 20%;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        margin: 0 3px;
        width: 40px;
        height: 40px;
        transition: all 0.3s ease;
        color: rgba(255, 255, 255, 0.8);
    }

    .social-icons a:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border-color: rgba(102, 126, 234, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .toggle-container {
        position: absolute;
        top: 0;
        left: 50%;
        width: 50%;
        height: 100%;
        overflow: hidden;
        transition: all 0.6s ease-in-out;
        border-radius: 150px 0 0 100px;
        z-index: 3;
    }

    .auth-container.active .toggle-container {
        transform: translateX(-100%);
        border-radius: 0 150px 100px 0;
        z-index: 3;
    }

    .toggle {
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        position: relative;
        left: -100%;
        height: 100%;
        width: 200%;
        transform: translateX(0);
        transition: all 0.6s ease-in-out;
    }

    .auth-container.active .toggle {
        transform: translateX(50%);
    }

    .toggle-panel {
        position: absolute;
        width: 50%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 30px;
        text-align: center;
        top: 0;
        transform: translateX(0);
        transition: all 0.6s ease-in-out;
        z-index: 10;
        box-sizing: border-box;
    }

    .toggle-left {
        transform: translateX(-200%);
    }

    .auth-container.active .toggle-left {
        transform: translateX(0);
    }

    .toggle-right {
        right: 0;
        transform: translateX(0);
    }

    .auth-container.active .toggle-right {
        transform: translateX(200%);
    }

    .toggle-panel h1 {
        color: #fff;
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 15px;
        z-index: 10;
        position: relative;
        padding: 0 20px;
    }

    .toggle-panel p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 14px;
        margin-bottom: 20px;
        padding: 0 20px;
        z-index: 10;
        position: relative;
        text-align: center;
        max-width: 100%;
        line-height: 1.6;
        width: 100%;
        box-sizing: border-box;
    }
    
    .toggle-logo {
        width: 80px;
        height: 80px;
        object-fit: contain;
        margin-bottom: 20px;
        z-index: 10;
        position: relative;
    }

    .form-check {
        width: 100%;
        max-width: 100%;
        text-align: left;
        margin: 10px 0;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-check-input {
        width: 14px !important;
        height: 14px !important;
        margin: 0 !important;
        flex-shrink: 0;
        cursor: pointer;
    }

    .form-check-label {
        font-size: 12px;
        margin: 0;
        cursor: pointer;
        color: rgba(255, 255, 255, 0.85);
    }

    .alert {
        margin-bottom: 20px;
        font-size: 13px;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.15em;
    }
    
    button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Form check input styling for dark theme */
    .auth-container .form-check-input {
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(102, 126, 234, 0.3);
    }

    .auth-container .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .auth-container .form-check-input:focus {
        border-color: rgba(102, 126, 234, 0.5);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    }

    @media (max-width: 768px) {
        .auth-container {
            width: 100%;
            border-radius: 0;
            min-height: 100vh;
        }

        .auth-container form {
            padding: 30px 25px 80px 25px;
        }

        .form-container {
            width: 100% !important;
        }

        .sign-in,
        .sign-up {
            width: 100% !important;
        }

        .auth-container.active .sign-in,
        .auth-container.active .sign-up {
            transform: translateX(0);
        }

        .toggle-container {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-container" id="authContainer">
    <div class="form-container sign-in">
        <form id="loginForm" method="POST">
            @csrf
            <h1>{{ __('app.auth.login') }}</h1>
            
            <div class="social-icons">
                <a href="{{ route('auth.google') }}" class="icon" title="Google">
                    <i class="fab fa-google"></i>
                </a>
                <a href="#" class="icon" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="icon" title="GitHub">
                    <i class="fab fa-github"></i>
                </a>
                <a href="#" class="icon" title="LinkedIn">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
            
            <span>{{ __('app.common.or') }} {{ __('app.auth.use_email_password') }}</span>
            
            <div id="login-alert-container"></div>
            
            <input type="email" id="login_email" name="email" placeholder="{{ __('app.auth.email') }}" required>
            
            <input type="password" id="login_password" name="password" placeholder="{{ __('app.auth.password') }}" required>
            
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">
                    {{ __('app.auth.remember_me') }}
                </label>
            </div>
            
            <a href="{{ route('auth.forgot.password') }}" style="text-align: right;">
                {{ __('app.auth.forgot_password') }}
            </a>
            
            <button type="submit" id="loginBtn">
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                {{ __('app.auth.login') }}
            </button>
        </form>
    </div>

    <div class="form-container sign-up">
        <form id="registerForm" method="POST">
            @csrf
            <h1>{{ __('app.auth.register') }}</h1>
            
            <div class="social-icons">
                <a href="{{ route('auth.google') }}" class="icon" title="Google">
                    <i class="fab fa-google"></i>
                </a>
                <a href="#" class="icon" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="icon" title="GitHub">
                    <i class="fab fa-github"></i>
                </a>
                <a href="#" class="icon" title="LinkedIn">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
            
            <span>{{ __('app.common.or') }} {{ __('app.auth.use_email_registration') }}</span>
            
            <div id="register-alert-container"></div>
            
            <input type="text" id="full_name" name="full_name" placeholder="{{ __('app.auth.full_name') }} *" required>
            
            <input type="email" id="email" name="email" placeholder="Email *" required>
            
            <input type="password" id="password" name="password" placeholder="{{ __('app.auth.password') }} *" required>
            
            <input type="password" id="confirm_password" name="confirm_password" placeholder="{{ __('app.auth.confirm_password') }} *" required>
            
            <button type="submit" id="registerBtn">
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                {{ __('app.auth.register') }}
            </button>
        </form>
    </div>

    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
                <img src="{{ asset('logo_remove_bg.png') }}" alt="{{ __('app.name') }}" class="toggle-logo mb-3">
                <h1>{{ __('app.auth.welcome_back') }}</h1>
                <p>{{ __('app.auth.enter_personal_details') }}</p>
                <button class="toggle-button" id="loginToggle">{{ __('app.auth.login') }}</button>
            </div>
            <div class="toggle-panel toggle-right">
                <img src="{{ asset('logo_remove_bg.png') }}" alt="{{ __('app.name') }}" class="toggle-logo mb-3">
                <h1>{{ __('app.auth.hello_friend') }}</h1>
                <p>{{ __('app.auth.register_personal_details') }}</p>
                <button class="toggle-button" id="registerToggle">{{ __('app.auth.register') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Translation keys for JavaScript
    const translations = {
        login_success: '{{ __('app.auth.login_success') }}',
        login_failed: '{{ __('app.auth.login_failed') }}',
        register_success: '{{ __('app.auth.register_success') }}',
        register_failed: '{{ __('app.auth.register_failed') }}',
        connection_error: '{{ __('app.auth.connection_error') }}',
        password_mismatch: '{{ __('app.auth.password_mismatch') }}',
        email_not_verified: '{{ __('app.auth.email_not_verified') }}',
        resend_email: '{{ __('app.auth.resend_email') }}',
        email_send_error: '{{ __('app.auth.connection_error') }}'
    };

    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('authContainer');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const loginBtn = document.getElementById('loginBtn');
        const registerBtn = document.getElementById('registerBtn');
        const loginSpinner = loginBtn.querySelector('.spinner-border');
        const registerSpinner = registerBtn.querySelector('.spinner-border');
        const loginAlertContainer = document.getElementById('login-alert-container');
        const registerAlertContainer = document.getElementById('register-alert-container');
        const loginToggle = document.getElementById('loginToggle');
        const registerToggle = document.getElementById('registerToggle');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');

        // Set CSRF token for all AJAX requests
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Determine initial state from server
        const initialMode = '{{ $mode ?? "login" }}';
        
        // Set initial state
        if (initialMode === 'register') {
            container.classList.add('active');
        }

        // Toggle to login
        if (loginToggle) {
            loginToggle.addEventListener('click', () => {
                container.classList.remove('active');
            });
        }

        // Toggle to register
        if (registerToggle) {
            registerToggle.addEventListener('click', () => {
                container.classList.add('active');
            });
        }

        // Password confirmation validation
        if (confirmPassword) {
            confirmPassword.addEventListener('input', function() {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity(translations.password_mismatch);
                } else {
                    confirmPassword.setCustomValidity('');
                }
            });
        }

        // Login form submission
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Show loading state
            loginBtn.disabled = true;
            loginSpinner.classList.remove('d-none');

            // Clear previous alerts
            loginAlertContainer.innerHTML = '';

            const formData = new FormData(loginForm);

            try {
                const response = await fetch('{{ route("auth.login.process") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showAlert(loginAlertContainer, translations.login_success, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect_url || '/dashboard';
                    }, 1000);
                } else {
                    if (data.requires_verification) {
                        showVerificationAlert(loginAlertContainer, data.message, data.email);
                    } else {
                        showAlert(loginAlertContainer, data.message || translations.login_failed, 'danger');
                    }
                }

            } catch (error) {
                console.error('Login error:', error);
                showAlert(loginAlertContainer, translations.connection_error, 'danger');
            } finally {
                // Hide loading state
                loginBtn.disabled = false;
                loginSpinner.classList.add('d-none');
            }
        });

        // Register form submission
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Show loading state
            registerBtn.disabled = true;
            registerSpinner.classList.remove('d-none');

            // Clear previous alerts
            registerAlertContainer.innerHTML = '';

            const formData = new FormData(registerForm);

            try {
                const response = await fetch('{{ route("auth.register.process") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showAlert(registerAlertContainer, translations.register_success, 'success');

                    // Lấy email từ form
                    const email = formData.get('email');
                    setTimeout(() => {
                        window.location.href = '{{ route("auth.check.email") }}?email=' + encodeURIComponent(email);
                    }, 2000);
                } else {
                    showAlert(registerAlertContainer, data.message || translations.register_failed, 'danger');
                }

            } catch (error) {
                console.error('Registration error:', error);
                showAlert(registerAlertContainer, translations.connection_error, 'danger');
            } finally {
                // Hide loading state
                registerBtn.disabled = false;
                registerSpinner.classList.add('d-none');
            }
        });

        function showAlert(container, message, type) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            container.appendChild(alert);
        }

        function showVerificationAlert(container, message, email) {
            const alert = document.createElement('div');
            alert.className = 'alert alert-warning alert-dismissible fade show';
            alert.innerHTML = `
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <strong><i class="fas fa-exclamation-triangle"></i> ${translations.email_not_verified}</strong><br>
                        ${message}
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="resendVerificationEmail('${email}')">
                        ${translations.resend_email}
                    </button>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            container.appendChild(alert);
        }

        // Function để gửi lại email xác nhận
        window.resendVerificationEmail = async function(email) {
            try {
                const response = await fetch('{{ route("auth.resend.verification") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        email: email
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showAlert(loginAlertContainer, data.message, 'success');
                } else {
                    showAlert(loginAlertContainer, data.message, 'danger');
                }
            } catch (error) {
                showAlert(loginAlertContainer, translations.email_send_error, 'danger');
            }
        }
    });
</script>
@endpush
