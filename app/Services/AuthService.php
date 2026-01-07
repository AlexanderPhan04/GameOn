<?php

namespace App\Services;

use App\Mail\VerifyEmail;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

/**
 * AuthService - Core authentication business logic
 * Extracted from AuthController for proper MVC architecture
 */
class AuthService
{
    /**
     * Attempt to login user with email and password
     *
     * @param string $email
     * @param string $password
     * @param bool $remember
     * @return array{success: bool, message: string, user?: User, redirect_url?: string, requires_verification?: bool, email?: string}
     */
    public function login(string $email, string $password, bool $remember = false): array
    {
        $credentials = [
            'email' => $email,
            'password' => $password,
        ];

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Check if email is verified
            if (is_null($user->email_verified_at)) {
                Auth::logout();
                
                return [
                    'success' => false,
                    'message' => 'Bạn cần xác nhận email trước khi đăng nhập. Vui lòng kiểm tra hộp thư của bạn.',
                    'requires_verification' => true,
                    'email' => $user->email,
                ];
            }

            // Set session for compatibility with existing system
            $this->setUserSession($user);

            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->user_role,
            ]);

            return [
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'user' => $user,
                'redirect_url' => $this->getRedirectUrlByRole($user->user_role),
            ];
        }

        return [
            'success' => false,
            'message' => 'Thông tin đăng nhập không chính xác',
        ];
    }

    /**
     * Register a new user
     *
     * @param array $data
     * @return array{success: bool, message: string, user?: User}
     */
    public function register(array $data): array
    {
        try {
            // Generate email verification token
            $emailVerificationToken = Str::random(60);

            // Create user
            $user = User::create([
                'name' => $data['full_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'user_role' => 'participant',
                'status' => 'active',
                'email_verification_token' => $emailVerificationToken,
                'email_verified_at' => null,
            ]);

            // Create user profile using Eloquent
            $idApp = 'USR' . str_pad($user->id, 6, '0', STR_PAD_LEFT);
            UserProfile::create([
                'user_id' => $user->id,
                'full_name' => $data['full_name'],
                'id_app' => $idApp,
            ]);

            // Send verification email
            $verificationUrl = url('/auth/verify-email/' . $emailVerificationToken);
            Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));

            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->user_role,
                'id_app' => $idApp,
            ]);

            return [
                'success' => true,
                'message' => 'Đăng ký thành công! Vui lòng kiểm tra email để xác nhận tài khoản.',
                'user' => $user,
            ];
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại',
            ];
        }
    }

    /**
     * Verify user email with token
     *
     * @param string $token
     * @return array{success: bool, message: string, user?: User}
     */
    public function verifyEmail(string $token): array
    {
        try {
            $user = User::where('email_verification_token', $token)
                ->whereNull('email_verified_at')
                ->first();

            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Link xác nhận không hợp lệ hoặc đã hết hạn.',
                ];
            }

            $user->email_verified_at = now();
            $user->email_verification_token = null;
            $user->save();

            Log::info('Email verified successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return [
                'success' => true,
                'message' => 'Email đã được xác nhận thành công! Bạn có thể đăng nhập ngay.',
                'user' => $user,
            ];
        } catch (\Exception $e) {
            Log::error('Email verification error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xác nhận email.',
            ];
        }
    }

    /**
     * Resend verification email
     *
     * @param string $email
     * @return array{success: bool, message: string}
     */
    public function resendVerificationEmail(string $email): array
    {
        try {
            $user = User::where('email', $email)
                ->whereNull('email_verified_at')
                ->first();

            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Email không tồn tại hoặc đã được xác nhận.',
                ];
            }

            // Generate new token
            $emailVerificationToken = Str::random(60);
            $user->email_verification_token = $emailVerificationToken;
            $user->save();

            // Send verification email
            $verificationUrl = url('/auth/verify-email/' . $emailVerificationToken);
            Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));

            return [
                'success' => true,
                'message' => 'Email xác nhận đã được gửi lại.',
            ];
        } catch (\Exception $e) {
            Log::error('Resend verification email error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi email.',
            ];
        }
    }

    /**
     * Logout user
     *
     * @return void
     */
    public function logout(): void
    {
        $username = Session::get('username');
        
        Session::flush();
        Auth::logout();

        if ($username) {
            Log::info('User logged out', ['username' => $username]);
        }
    }

    /**
     * Set user session data
     *
     * @param User $user
     * @return void
     */
    public function setUserSession(User $user): void
    {
        Session::put('user_id', $user->id);
        Session::put('username', $user->name);
        Session::put('role', $user->user_role);
    }

    /**
     * Get redirect URL based on user role
     *
     * @param string|null $role
     * @return string
     */
    public function getRedirectUrlByRole(?string $role): string
    {
        return match ($role) {
            'super_admin' => '/dashboard',
            'admin' => '/dashboard',
            'participant' => '/posts',
            default => '/posts'
        };
    }
}
