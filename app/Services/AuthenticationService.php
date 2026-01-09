<?php

namespace App\Services;

use App\Http\DTOs\LoginDto;
use App\Http\DTOs\RegisterDto;
use App\Http\Results\AuthenticationResult;
use App\Models\EsportsUser;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * AuthenticationService - Xử lý đăng nhập và xác thực người dùng
 * Chuyển đổi từ EsportsManager.BL.Services.AuthenticationService
 * Theo tài liệu nghiệp vụ: Role-based Authentication
 */
class AuthenticationService
{
    /**
     * Đăng nhập với username/password
     * Theo logic tài liệu: kiểm tra account approval, role-based access
     */
    public function login(LoginDto $loginDto): AuthenticationResult
    {
        try {
            Log::info('Login attempt for user: ' . $loginDto->username);

            // 1. Validate input
            $errors = $loginDto->validate();
            if (! empty($errors)) {
                return AuthenticationResult::failure(implode(', ', $errors));
            }

            // 2. Tìm user theo username
            $user = EsportsUser::where('username', $loginDto->username)->first();
            if (! $user) {
                Log::warning('Login failed: User not found - ' . $loginDto->username);

                return AuthenticationResult::failure('Tên đăng nhập hoặc mật khẩu không đúng');
            }

            // 3. Kiểm tra trạng thái tài khoản
            if ($user->status === EsportsUser::STATUS_DELETED) {
                Log::warning('Login failed: User deleted - ' . $loginDto->username);

                return AuthenticationResult::failure('Tài khoản đã bị xóa');
            }

            if ($user->status === EsportsUser::STATUS_SUSPENDED) {
                Log::warning('Login failed: User suspended - ' . $loginDto->username);

                return AuthenticationResult::failure('Tài khoản đã bị tạm khóa');
            }

            // Bỏ kiểm tra Pending - cho phép đăng nhập ngay lập tức

            // 4. Xác minh mật khẩu
            if (! Hash::check($loginDto->password, $user->password_hash)) {
                Log::warning('Login failed: Invalid password - ' . $loginDto->username);

                return AuthenticationResult::failure('Tên đăng nhập hoặc mật khẩu không đúng');
            }

            // 5. Cập nhật thời gian đăng nhập cuối
            $user->updateLastLogin();

            // 6. Đảm bảo role hiển thị bằng tiếng Việt
            $displayRole = $this->getRoleDisplayName($user->role);

            Log::info('Login successful for user: ' . $user->username . ', Role: ' . $displayRole);

            return AuthenticationResult::success($user->user_id, $user->username, $displayRole);
        } catch (Exception $ex) {
            Log::error('Error during login for user: ' . ($loginDto->username ?? 'unknown'), [
                'exception' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString(),
            ]);

            return AuthenticationResult::failure('Lỗi hệ thống. Vui lòng thử lại sau.');
        }
    }

    /**
     * Đăng ký tài khoản mới
     * Theo tài liệu: Account tự động được kích hoạt (không cần Admin duyệt)
     */
    public function register(RegisterDto $registerDto): AuthenticationResult
    {
        try {
            // 1. Comprehensive validation using RegisterDto.validateAll()
            $validationErrors = $registerDto->validateAll();
            if (! empty($validationErrors)) {
                return AuthenticationResult::failure('Dữ liệu không hợp lệ: ' . implode(', ', $validationErrors));
            }

            // 2. Check if username exists
            if (EsportsUser::where('username', $registerDto->username)->exists()) {
                return AuthenticationResult::failure('Tên đăng nhập đã tồn tại');
            }

            // 3. Check if email exists
            if (EsportsUser::where('email', $registerDto->email)->exists()) {
                return AuthenticationResult::failure('Email đã được sử dụng');
            }

            // 4. Validate role
            $userRole = $registerDto->role;
            $validRoles = [EsportsUser::ROLE_PARTICIPANT];
            if (! in_array($userRole, $validRoles)) {
                $userRole = EsportsUser::ROLE_PARTICIPANT; // Default fallback
            }

            Log::debug('Creating user: ' . $registerDto->username .
                ', SecurityQuestion: ' . $registerDto->securityQuestion .
                ', SecurityAnswer: ' . $registerDto->securityAnswer);

            // 5. Create user
            $user = EsportsUser::create([
                'username' => $registerDto->username,
                'email' => $registerDto->email,
                'password_hash' => $registerDto->password, // Will be hashed by model mutator
                'full_name' => $registerDto->fullName ?: null,
                'role' => $userRole,
                'status' => EsportsUser::STATUS_ACTIVE, // Tự động kích hoạt tài khoản mới
                'security_question' => $registerDto->securityQuestion,
                'security_answer' => $registerDto->securityAnswer, // Will be hashed by model mutator
                'is_email_verified' => false,
            ]);

            Log::info('User registered successfully: ' . $user->username);

            return AuthenticationResult::success($user->user_id, $user->username, $user->role);
        } catch (Exception $ex) {
            Log::error('Error during user registration', [
                'username' => $registerDto->username ?? 'unknown',
                'exception' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString(),
            ]);

            return AuthenticationResult::failure('Lỗi hệ thống khi đăng ký. Vui lòng thử lại sau.');
        }
    }

    /**
     * Xác minh mật khẩu
     */
    private function verifyPassword(string $password, string $hashedPassword): bool
    {
        return Hash::check($password, $hashedPassword);
    }

    /**
     * Lấy tên hiển thị của role bằng tiếng Việt
     */
    private function getRoleDisplayName(string $role): string
    {
        return match ($role) {
            EsportsUser::ROLE_ADMIN => 'Quản trị viên',
            EsportsUser::ROLE_PARTICIPANT => 'Người tham gia',
            default => 'Không xác định'
        };
    }

    /**
     * Kiểm tra người dùng có tồn tại không
     */
    public function userExists(string $username): bool
    {
        return EsportsUser::where('username', $username)->exists();
    }

    /**
     * Kiểm tra email có tồn tại không
     */
    public function emailExists(string $email): bool
    {
        return EsportsUser::where('email', $email)->exists();
    }

    /**
     * Lấy thông tin người dùng theo username
     */
    public function getUserByUsername(string $username): ?EsportsUser
    {
        return EsportsUser::where('username', $username)->first();
    }

    /**
     * Lấy thông tin người dùng theo user ID
     */
    public function getUserById(int $userId): ?EsportsUser
    {
        return EsportsUser::find($userId);
    }
}
