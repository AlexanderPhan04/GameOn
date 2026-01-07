<?php

namespace App\Services;

use App\Mail\ForgotPasswordEmail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * PasswordResetService - Password reset business logic
 * Extracted from AuthController for proper MVC architecture
 */
class PasswordResetService
{
    /**
     * Send password reset link to user email
     *
     * @param string $email
     * @return array{success: bool, message: string}
     */
    public function sendResetLink(string $email): array
    {
        try {
            $user = User::where('email', $email)->first();

            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Email không tồn tại trong hệ thống',
                ];
            }

            // Generate reset token
            $token = Str::random(64);

            // Delete old token if exists
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            // Create new token
            DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => now(),
                'expires_at' => now()->addMinutes(60),
            ]);

            // Create reset URL
            $resetUrl = url('/auth/reset-password?token=' . $token . '&email=' . urlencode($email));

            // Send email
            Mail::to($user->email)->send(new ForgotPasswordEmail($user, $resetUrl, $token));

            Log::info('Password reset email sent', [
                'email' => $user->email,
                'token' => substr($token, 0, 10) . '...',
            ]);

            return [
                'success' => true,
                'message' => 'Link đặt lại mật khẩu đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư.',
            ];
        } catch (\Exception $e) {
            Log::error('Forgot password error: ' . $e->getMessage(), [
                'email' => $email,
            ]);

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi email. Vui lòng thử lại.',
            ];
        }
    }

    /**
     * Validate reset token
     *
     * @param string $email
     * @param string $token
     * @return bool
     */
    public function validateToken(string $email, string $token): bool
    {
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        return $resetRecord !== null;
    }

    /**
     * Reset user password
     *
     * @param string $email
     * @param string $token
     * @param string $newPassword
     * @return array{success: bool, message: string}
     */
    public function resetPassword(string $email, string $token, string $newPassword): array
    {
        try {
            // Validate token
            if (!$this->validateToken($email, $token)) {
                return [
                    'success' => false,
                    'message' => 'Token không hợp lệ hoặc đã hết hạn',
                ];
            }

            // Find user
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng',
                ];
            }

            // Update password
            $user->password = Hash::make($newPassword);
            $user->save();

            // Delete used token
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            Log::info('Password reset successful', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return [
                'success' => true,
                'message' => 'Mật khẩu đã được đặt lại thành công. Bạn có thể đăng nhập với mật khẩu mới.',
            ];
        } catch (\Exception $e) {
            Log::error('Reset password error: ' . $e->getMessage(), [
                'email' => $email,
            ]);

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đặt lại mật khẩu. Vui lòng thử lại.',
            ];
        }
    }
}
