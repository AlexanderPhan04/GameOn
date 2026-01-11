<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;

/**
 * GoogleAuthService - Google OAuth business logic
 * Extracted from AuthController for proper MVC architecture
 */
class GoogleAuthService
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle Google OAuth callback - login or create user
     *
     * @param SocialiteUser $googleUser
     * @return array{success: bool, message: string, user?: User, redirect_url?: string, error?: string}
     */
    public function handleCallback(SocialiteUser $googleUser): array
    {
        try {
            $googleId = $googleUser->getId();
            $googleEmail = $googleUser->getEmail();
            $googleName = $googleUser->getName();
            $googleAvatar = $googleUser->getAvatar();

            // Check for existing user by email
            $user = User::where('email', $googleEmail)->first();

            if ($user) {
                // Update Google ID if not set
                $this->linkGoogleIdToUser($user, $googleId, $googleEmail);
                
                // Sync Google avatar
                $this->syncAvatar($user, $googleAvatar);
            } else {
                // Validate Google data
                if (empty($googleName) || empty($googleEmail) || empty($googleId)) {
                    Log::error('Google user data incomplete', [
                        'name' => $googleName,
                        'email' => $googleEmail,
                        'google_id' => $googleId,
                    ]);

                    return [
                        'success' => false,
                        'message' => 'Thông tin tài khoản Google không đầy đủ. Vui lòng thử lại.',
                    ];
                }

                // Create new user
                $user = $this->createUserFromGoogle($googleUser);
                
                if (!$user) {
                    return [
                        'success' => false,
                        'message' => 'Không thể tạo tài khoản từ Google. Vui lòng thử lại.',
                    ];
                }
            }

            // Login user
            Auth::login($user);
            $this->authService->setUserSession($user);

            return [
                'success' => true,
                'message' => 'Đăng nhập Google thành công',
                'user' => $user,
                'redirect_url' => $this->authService->getRedirectUrlByRole($user->user_role),
            ];
        } catch (\Exception $e) {
            Log::error('Google OAuth error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Đăng nhập với Google thất bại',
            ];
        }
    }

    /**
     * Create new user from Google data
     *
     * @param SocialiteUser $googleUser
     * @return User|null
     */
    public function createUserFromGoogle(SocialiteUser $googleUser): ?User
    {
        try {
            $googleName = $googleUser->getName();
            $googleEmail = $googleUser->getEmail();
            $googleId = $googleUser->getId();
            $googleAvatar = $googleUser->getAvatar();

            // Create user using Eloquent
            $user = User::create([
                'name' => $googleName,
                'email' => $googleEmail,
                'google_id' => $googleId,
                'google_email' => $googleEmail,
                'password' => Hash::make(Str::random(32)),
                'user_role' => 'participant',
                'status' => 'active',
                'email_verified_at' => now(), // Google account is verified
            ]);

            // Download Google avatar to local storage
            $localAvatarPath = $this->downloadGoogleAvatar($googleAvatar, $user->id);

            // Create user profile using Eloquent
            $idApp = 'GGL' . str_pad($user->id, 6, '0', STR_PAD_LEFT);
            UserProfile::create([
                'user_id' => $user->id,
                'full_name' => $googleName,
                'avatar' => $localAvatarPath, // Use local path instead of URL
                'google_avatar' => $googleAvatar, // Store original Google avatar URL for reference
                'id_app' => $idApp,
            ]);

            Log::info('New Google user created', [
                'user_id' => $user->id,
                'email' => $googleEmail,
                'google_id' => $googleId,
                'id_app' => $idApp,
            ]);

            return $user;
        } catch (\Exception $e) {
            Log::error('Failed to create Google user', [
                'error' => $e->getMessage(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
            ]);

            return null;
        }
    }

    /**
     * Link Google ID to existing user
     *
     * @param User $user
     * @param string $googleId
     * @param string $googleEmail
     * @return void
     */
    public function linkGoogleIdToUser(User $user, string $googleId, string $googleEmail): void
    {
        if (!$user->google_id) {
            $user->google_id = $googleId;
            $user->google_email = $googleEmail;
            $user->save();

            Log::info('Google ID linked to user', [
                'user_id' => $user->id,
                'google_id' => $googleId,
            ]);
        }
    }

    /**
     * Sync Google avatar to user profile
     *
     * @param User $user
     * @param string|null $googleAvatar
     * @return void
     */
    public function syncAvatar(User $user, ?string $googleAvatar): void
    {
        if (!$googleAvatar || !$user->profile) {
            return;
        }

        $currentAvatar = $user->profile->avatar;
        $updateData = [];
        
        // Always update google_avatar URL to keep it fresh for user to choose later
        if ($user->profile->google_avatar !== $googleAvatar) {
            $updateData['google_avatar'] = $googleAvatar;
        }
        
        // Only update avatar if user has no avatar or current is a Google URL (needs refresh)
        if (!$currentAvatar || (filter_var($currentAvatar, FILTER_VALIDATE_URL) && str_contains($currentAvatar, 'googleusercontent.com'))) {
            // Download Google avatar to local storage
            $localAvatarPath = $this->downloadGoogleAvatar($googleAvatar, $user->id);
            if ($localAvatarPath) {
                $updateData['avatar'] = $localAvatarPath;
            }
        }

        if (!empty($updateData)) {
            $user->profile->update($updateData);

            Log::info('Google avatar synced for user', [
                'user_id' => $user->id,
                'avatar_url' => substr($googleAvatar, 0, 50) . '...',
            ]);
        }
    }

    /**
     * Download Google avatar to local storage
     *
     * @param string|null $googleAvatarUrl
     * @param int $userId
     * @return string|null Local path or null if failed
     */
    protected function downloadGoogleAvatar(?string $googleAvatarUrl, int $userId): ?string
    {
        if (!$googleAvatarUrl) {
            return null;
        }

        try {
            $imageContents = @file_get_contents($googleAvatarUrl);
            if ($imageContents) {
                $filename = 'avatars/google_' . $userId . '_' . time() . '.jpg';
                \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $imageContents);
                return $filename;
            }
        } catch (\Exception $e) {
            Log::warning('Failed to download Google avatar', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Link Google account to existing user
     *
     * @param User $user
     * @param SocialiteUser $googleUser
     * @return array{success: bool, message: string}
     */
    public function linkAccount(User $user, SocialiteUser $googleUser): array
    {
        try {
            $googleId = $googleUser->getId();
            $googleEmail = $googleUser->getEmail();

            // Check if Google ID is already used by another user
            $existingUser = User::where('google_id', $googleId)
                ->where('id', '!=', $user->id)
                ->first();

            if ($existingUser) {
                return [
                    'success' => false,
                    'message' => 'Tài khoản Google này đã được liên kết với người dùng khác.',
                ];
            }

            // Check if user already has different Google ID
            if ($user->google_id && $user->google_id !== $googleId) {
                return [
                    'success' => false,
                    'message' => 'Tài khoản của bạn đã được liên kết với Google ID khác.',
                ];
            }

            // Link Google account
            $user->google_id = $googleId;
            $user->google_email = $googleEmail;
            $user->save();

            // Sync avatar
            $this->syncAvatar($user, $googleUser->getAvatar());

            Log::info('Google account linked', [
                'user_id' => $user->id,
                'google_id' => $googleId,
            ]);

            return [
                'success' => true,
                'message' => 'Liên kết tài khoản Google thành công!',
            ];
        } catch (\Exception $e) {
            Log::error('Google link error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi liên kết tài khoản Google.',
            ];
        }
    }

    /**
     * Unlink Google account from user
     *
     * @param User $user
     * @return array{success: bool, message: string}
     */
    public function unlinkAccount(User $user): array
    {
        try {
            // Check if user has password (can login without Google)
            if (!$user->password || $user->password === '') {
                return [
                    'success' => false,
                    'message' => 'Bạn cần đặt mật khẩu trước khi hủy liên kết Google.',
                ];
            }

            $user->google_id = null;
            $user->google_email = null;
            $user->save();

            Log::info('Google account unlinked', ['user_id' => $user->id]);

            return [
                'success' => true,
                'message' => 'Đã hủy liên kết tài khoản Google.',
            ];
        } catch (\Exception $e) {
            Log::error('Google unlink error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy liên kết.',
            ];
        }
    }

    /**
     * Check for Google account conflicts
     *
     * @param string $googleId
     * @param string $googleEmail
     * @param int|null $excludeUserId
     * @return array{conflict: bool, type?: string, message?: string, existing_user?: User}
     */
    public function checkConflicts(string $googleId, string $googleEmail, ?int $excludeUserId = null): array
    {
        // Check if Google ID is already used
        $query = User::where('google_id', $googleId);
        if ($excludeUserId) {
            $query->where('id', '!=', $excludeUserId);
        }
        $existingByGoogleId = $query->first();

        if ($existingByGoogleId) {
            return [
                'conflict' => true,
                'type' => 'google_id',
                'message' => 'Tài khoản Google này đã được liên kết với người dùng khác.',
                'existing_user' => $existingByGoogleId,
            ];
        }

        // Check if email is already used
        $query = User::where('email', $googleEmail);
        if ($excludeUserId) {
            $query->where('id', '!=', $excludeUserId);
        }
        $existingByEmail = $query->first();

        if ($existingByEmail && !$existingByEmail->google_id) {
            return [
                'conflict' => true,
                'type' => 'email',
                'message' => 'Email này đã được sử dụng bởi tài khoản khác.',
                'existing_user' => $existingByEmail,
            ];
        }

        return ['conflict' => false];
    }
}
