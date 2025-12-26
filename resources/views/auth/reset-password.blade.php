<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đặt lại mật khẩu - Esport Manager</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
            padding: 20px;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .auth-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 30px 20px;
            text-align: center;
        }

        .auth-body {
            padding: 30px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
        }

        .password-strength {
            margin-top: 8px;
        }

        .strength-bar {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-text {
            font-size: 12px;
            margin-top: 4px;
        }

        .requirements {
            font-size: 12px;
            margin-top: 8px;
        }

        .requirements ul {
            margin: 0;
            padding-left: 20px;
        }

        .requirements .valid {
            color: #28a745;
        }

        .requirements .invalid {
            color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h3 class="mb-2">
                    <i class="fas fa-lock me-2"></i>Đặt lại mật khẩu
                </h3>
                <p class="mb-0 opacity-75">Tạo mật khẩu mới cho tài khoản của bạn</p>
            </div>

            <div class="auth-body">
                <div id="alert-container"></div>

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="mb-4">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-user me-2"></i>
                        <strong>Email:</strong> {{ $email }}
                    </div>
                </div>

                <form id="resetPasswordForm" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-key me-1"></i>Mật khẩu mới
                        </label>
                        <div class="position-relative">
                            <input type="password" class="form-control" id="password" name="password" required
                                placeholder="Nhập mật khẩu mới">
                            <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2"
                                id="togglePassword" style="border: none; background: none;">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>

                        <!-- Password strength indicator -->
                        <div class="password-strength">
                            <div class="strength-bar bg-light" id="strengthBar"></div>
                            <div class="strength-text" id="strengthText"></div>
                        </div>

                        <!-- Password requirements -->
                        <div class="requirements text-muted">
                            <small>Mật khẩu phải có:</small>
                            <ul>
                                <li id="req-length" class="invalid">
                                    <i class="fas fa-times"></i> Ít nhất 8 ký tự
                                </li>
                                <li id="req-uppercase" class="invalid">
                                    <i class="fas fa-times"></i> Ít nhất 1 chữ hoa
                                </li>
                                <li id="req-lowercase" class="invalid">
                                    <i class="fas fa-times"></i> Ít nhất 1 chữ thường
                                </li>
                                <li id="req-number" class="invalid">
                                    <i class="fas fa-times"></i> Ít nhất 1 số
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-check-double me-1"></i>Xác nhận mật khẩu
                        </label>
                        <input type="password" class="form-control" id="password_confirmation"
                            name="password_confirmation" required
                            placeholder="Nhập lại mật khẩu mới">
                        <div class="form-text" id="confirmationText"></div>
                    </div>

                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <i class="fas fa-save me-2"></i>Đặt lại mật khẩu
                        </button>
                    </div>
                </form>

                <div class="text-center">
                    <a href="{{ route('auth.login') }}" class="text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại đăng nhập
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('resetPasswordForm');
            const submitBtn = document.getElementById('submitBtn');
            const spinner = submitBtn.querySelector('.spinner-border');
            const alertContainer = document.getElementById('alert-container');
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const togglePassword = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Toggle password visibility
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                eyeIcon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
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

                const colors = ['#dc3545', '#fd7e14', '#ffc107', '#28a745'];
                const texts = ['Rất yếu', 'Yếu', 'Trung bình', 'Mạnh'];

                if (score === 0) {
                    strengthBar.style.width = '0%';
                    strengthBar.style.backgroundColor = '#e9ecef';
                    strengthText.textContent = '';
                } else {
                    strengthBar.style.width = (score * 25) + '%';
                    strengthBar.style.backgroundColor = colors[score - 1];
                    strengthText.textContent = texts[score - 1];
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
                    confirmationText.textContent = '✓ Mật khẩu khớp';
                    confirmationText.className = 'form-text text-success';
                } else {
                    confirmationText.textContent = '✗ Mật khẩu không khớp';
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
                        showAlert(data.message || 'Có lỗi xảy ra', 'danger');
                        submitBtn.disabled = false;
                    }

                } catch (error) {
                    console.error('Reset password error:', error);
                    showAlert('Lỗi kết nối. Vui lòng thử lại.', 'danger');
                    submitBtn.disabled = false;
                } finally {
                    // Hide loading state
                    spinner.classList.add('d-none');
                }
            });

            function showAlert(message, type) {
                const alert = document.createElement('div');
                alert.className = `alert alert-${type} alert-dismissible fade show`;
                alert.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                alertContainer.appendChild(alert);
            }
        });
    </script>
</body>

</html>