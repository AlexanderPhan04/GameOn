@extends('layouts.app')

@section('title', 'Quên mật khẩu - Game On')

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

    .forgot-password-container {
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

    .forgot-password-header {
        background: linear-gradient(135deg, #000022 0%, #000055 50%, #000022 100%);
        padding: 40px 30px 30px;
        text-align: center;
        position: relative;
    }
    
    .forgot-password-header::after {
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

    .forgot-password-header h3 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 28px;
        font-weight: 700;
        color: #00E5FF;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
        text-shadow: 0 0 20px rgba(0, 229, 255, 0.5);
    }

    .forgot-password-header p {
        color: #94a3b8;
        font-size: 14px;
        margin: 0;
        line-height: 1.6;
    }

    .forgot-password-body {
        padding: 30px 40px 40px;
    }

    .info-box {
        background: rgba(0, 229, 255, 0.05);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 25px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    
    .info-box i {
        color: #00E5FF;
        font-size: 16px;
        margin-top: 2px;
    }
    
    .info-box p {
        color: #94a3b8;
        font-size: 13px;
        margin: 0;
        line-height: 1.5;
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
    
    .form-text {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        color: #64748b;
        font-size: 12px;
    }
    
    .form-text i {
        color: #64748b;
        font-size: 11px;
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

    .btn-submit:hover {
        background: linear-gradient(135deg, #000077 0%, #0000aa 100%) !important;
        border-color: #00E5FF !important;
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.5), 0 6px 15px rgba(0, 229, 255, 0.3);
        transform: translateY(-2px);
    }
    
    .btn-submit:disabled {
        opacity: 0.6;
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

    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(0, 229, 255, 0.3), transparent);
        margin: 25px 0;
    }

    .timer-info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #64748b;
        font-size: 12px;
    }
    
    .timer-info i {
        color: #00E5FF;
    }

    .alert {
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 20px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #10b981;
    }
    
    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }
    
    .alert-info {
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.3);
        color: #00E5FF;
    }
    
    .btn-close {
        filter: invert(1);
        opacity: 0.5;
    }

    @media (max-width: 576px) {
        .forgot-password-container {
            border-radius: 20px;
            margin: 0 15px;
        }
        
        .forgot-password-header {
            padding: 30px 20px 25px;
        }
        
        .forgot-password-body {
            padding: 25px 20px 30px;
        }
        
        .forgot-password-header h3 {
            font-size: 24px;
        }
    }
</style>
@endpush

@section('content')
<div class="forgot-password-container">
    <div class="forgot-password-header">
        <div class="header-icon">
            <i class="fas fa-key"></i>
        </div>
        <h3>Quên mật khẩu</h3>
        <p>Nhập email để đặt lại mật khẩu của bạn</p>
    </div>

    <div class="forgot-password-body">
        <div id="alert-container"></div>

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <p>Chúng tôi sẽ gửi link đặt lại mật khẩu đến email của bạn. Vui lòng kiểm tra cả thư mục spam.</p>
        </div>

        <form id="forgotPasswordForm" method="POST">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i>
                    <span>Địa chỉ Email</span>
                </label>
                <input type="email" class="form-control" id="email" name="email" required
                    placeholder="Nhập địa chỉ email của bạn">
                <div class="form-text">
                    <i class="fas fa-shield-alt"></i>
                    <span>Email phải là địa chỉ đã đăng ký tài khoản</span>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                <i class="fas fa-paper-plane"></i>
                <span>Gửi link đặt lại mật khẩu</span>
            </button>
        </form>

        <a href="{{ route('auth.login') }}" class="back-to-login">
            <i class="fas fa-arrow-left"></i>
            <span>Quay lại đăng nhập</span>
        </a>

        <div class="divider"></div>

        <div class="timer-info">
            <i class="fas fa-clock"></i>
            <span>Link đặt lại mật khẩu có hiệu lực trong 60 phút</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
            const iconMap = {
                'success': 'fas fa-check-circle',
                'danger': 'fas fa-exclamation-circle',
                'info': 'fas fa-info-circle'
            };
            
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                <i class="${iconMap[type] || 'fas fa-info-circle'}"></i>
                <span>${message}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            alertContainer.appendChild(alert);
        }
    });
</script>
@endpush