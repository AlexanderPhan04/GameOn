<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quên mật khẩu - Esport Manager</title>
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
            max-width: 400px;
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

        .alert {
            margin-bottom: 20px;
        }

        .back-to-login {
            text-decoration: none;
            color: #667eea;
            font-weight: 500;
        }

        .back-to-login:hover {
            color: #764ba2;
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h3 class="mb-2">
                    <i class="fas fa-key me-2"></i>Quên mật khẩu
                </h3>
                <p class="mb-0 opacity-75">Nhập email để đặt lại mật khẩu</p>
            </div>

            <div class="auth-body">
                <div id="alert-container"></div>

                <div class="mb-4">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Chúng tôi sẽ gửi link đặt lại mật khẩu đến email của bạn.</small>
                    </div>
                </div>

                <form id="forgotPasswordForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i>Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" required
                            placeholder="Nhập địa chỉ email của bạn">
                        <div class="form-text">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Email phải là địa chỉ đã đăng ký tài khoản
                            </small>
                        </div>
                    </div>

                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <i class="fas fa-paper-plane me-2"></i>Gửi link đặt lại mật khẩu
                        </button>
                    </div>
                </form>

                <div class="text-center">
                    <a href="{{ route('auth.login') }}" class="back-to-login">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại đăng nhập
                    </a>
                </div>

                <hr class="my-4">

                <div class="text-center">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Link đặt lại mật khẩu có hiệu lực trong 60 phút
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('forgotPasswordForm');
            const submitBtn = document.getElementById('submitBtn');
            const spinner = submitBtn.querySelector('.spinner-border');
            const alertContainer = document.getElementById('alert-container');

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Show loading state
                submitBtn.disabled = true;
                spinner.classList.remove('d-none');

                // Clear previous alerts
                alertContainer.innerHTML = '';

                const formData = new FormData(form);

                try {
                    const response = await fetch('{{ route("auth.forgot.password.process") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        showAlert(data.message, 'success');
                        form.reset();

                        // Redirect sau 3 giây
                        setTimeout(() => {
                            window.location.href = '{{ route("auth.login") }}';
                        }, 3000);
                    } else {
                        showAlert(data.message || 'Có lỗi xảy ra', 'danger');
                    }

                } catch (error) {
                    console.error('Forgot password error:', error);
                    showAlert('Lỗi kết nối. Vui lòng thử lại.', 'danger');
                } finally {
                    // Hide loading state
                    submitBtn.disabled = false;
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