<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiểm tra email - Esport Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .verify-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            text-align: center;
            max-width: 500px;
            margin: 0 auto;
        }

        .icon-envelope {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 1.5rem;
        }

        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
        }

        .btn-custom:hover {
            color: white;
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="verify-card">
                    <div class="icon-envelope">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>

                    <h2 class="mb-4">Kiểm tra email của bạn</h2>

                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Đăng ký thành công!</strong>
                    </div>

                    <p class="text-muted mb-4">
                        Chúng tôi đã gửi một email xác nhận đến địa chỉ email của bạn.
                        Vui lòng kiểm tra hộp thư (bao gồm cả thư mục spam) và nhấp vào link xác nhận để kích hoạt tài khoản.
                    </p>

                    <div class="mb-4">
                        <h5>📋 Những gì bạn cần làm:</h5>
                        <ol class="text-start">
                            <li>Kiểm tra hộp thư email ({{ $email ?? 'địa chỉ email của bạn' }})</li>
                            <li>Tìm email từ "Esport Manager"</li>
                            <li>Nhấp vào nút "Xác nhận địa chỉ email"</li>
                            <li>Quay lại và đăng nhập</li>
                        </ol>
                    </div>

                    <div class="alert alert-warning" role="alert">
                        <small>
                            <i class="fas fa-clock me-1"></i>
                            Link xác nhận có hiệu lực trong 24 giờ
                        </small>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('auth.login') }}" class="btn btn-custom btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Đến trang đăng nhập
                        </a>

                        <button type="button" class="btn btn-outline-secondary" id="resendBtn">
                            <i class="fas fa-paper-plane me-2"></i>Gửi lại email xác nhận
                        </button>
                    </div>

                    <hr class="my-4">

                    <div class="text-muted">
                        <small>
                            <i class="fas fa-question-circle me-1"></i>
                            Gặp vấn đề? <a href="mailto:{{ config('mail.from.address') }}">Liên hệ với chúng tôi</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('resendBtn').addEventListener('click', function() {
            const email = '{{ $email ?? "" }}';
            if (!email) {
                alert('Email không hợp lệ');
                return;
            }

            const btn = this;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang gửi...';

            fetch('{{ route("auth.resend.verification") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        btn.className = 'btn btn-success';
                        btn.innerHTML = '<i class="fas fa-check me-2"></i>Đã gửi!';
                    } else {
                        btn.className = 'btn btn-danger';
                        btn.innerHTML = '<i class="fas fa-times me-2"></i>Lỗi';
                    }

                    setTimeout(() => {
                        btn.disabled = false;
                        btn.className = 'btn btn-outline-secondary';
                        btn.innerHTML = originalText;
                    }, 3000);
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.className = 'btn btn-danger';
                    btn.innerHTML = '<i class="fas fa-times me-2"></i>Lỗi mạng';

                    setTimeout(() => {
                        btn.className = 'btn btn-outline-secondary';
                        btn.innerHTML = originalText;
                    }, 3000);
                });
        });
    </script>
</body>

</html>