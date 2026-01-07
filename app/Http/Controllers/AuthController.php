<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordEmail;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Services\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

/**
 * AuthController - Xử lý authentication endpoints
 * Chuyển đổi từ EsportsManager.UI.Controllers tương ứng
 */
class AuthController extends Controller
{
    private AuthenticationService $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Kiểm tra tài khoản Google có trùng lặp không
     */
    private function checkGoogleAccountConflicts($googleId, $googleEmail, $excludeUserId = null)
    {
        // Kiểm tra Google ID trùng lặp
        $googleIdQuery = User::where('google_id', $googleId);
        if ($excludeUserId) {
            $googleIdQuery->where('id', '!=', $excludeUserId);
        }
        $existingGoogleId = $googleIdQuery->first();

        if ($existingGoogleId) {
            return [
                'conflict' => true,
                'type' => 'google_id',
                'message' => 'Tài khoản Google này đã được liên kết với tài khoản khác.',
                'existing_user' => $existingGoogleId,
            ];
        }

        // Kiểm tra Google email trùng lặp
        $googleEmailQuery = User::where('google_email', $googleEmail);
        if ($excludeUserId) {
            $googleEmailQuery->where('id', '!=', $excludeUserId);
        }
        $existingGoogleEmail = $googleEmailQuery->first();

        if ($existingGoogleEmail) {
            return [
                'conflict' => true,
                'type' => 'google_email',
                'message' => 'Email Google này đã được sử dụng bởi tài khoản khác.',
                'existing_user' => $existingGoogleEmail,
            ];
        }

        return ['conflict' => false];
    }

    /**
     * Debug và log thông tin Google user
     */
    private function debugGoogleUser($googleUser, $context = 'unknown')
    {
        $debugInfo = [
            'context' => $context,
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
            'raw_data' => $googleUser->getRaw(),
        ];

        Log::info('Google User Debug Info', $debugInfo);

        return $debugInfo;
    }

    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        return view('auth.auth', ['mode' => 'login']);
    }

    /**
     * Xử lý đăng nhập với email thay vì username
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Kiểm tra email đã được xác nhận
            if (is_null($user->email_verified_at)) {
                Auth::logout();

                return response()->json([
                    'success' => false,
                    'message' => 'Bạn cần xác nhận email trước khi đăng nhập. Vui lòng kiểm tra hộp thư của bạn.',
                    'requires_verification' => true,
                    'email' => $user->email,
                ], 400);
            }

            // Set session for compatibility with existing system
            Session::put('user_id', $user->id);
            Session::put('username', $user->name);
            Session::put('role', $user->user_role);

            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->user_role,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->user_role,
                ],
                'redirect_url' => $this->getRedirectUrlByRole($user->user_role),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Thông tin đăng nhập không chính xác',
        ], 400);
    }

    /**
     * Hiển thị form đăng ký
     */
    public function showRegisterForm()
    {
        return view('auth.auth', ['mode' => 'register']);
    }

    /**
     * Xử lý đăng ký với thông tin đã đơn giản hóa
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).*$/',
            ],
            'confirm_password' => 'required|same:password',
        ], [
            'full_name.required' => 'Họ và tên là bắt buộc',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ thường, 1 chữ hoa và 1 số',
            'confirm_password.required' => 'Xác nhận mật khẩu là bắt buộc',
            'confirm_password.same' => 'Xác nhận mật khẩu không khớp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {
            // Tạo token xác nhận email
            $emailVerificationToken = Str::random(60);

            // Default role for new users is participant
            $userRole = 'participant';

            // 1. Create user
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_role' => $userRole,
                'status' => 'active',
                'email_verification_token' => $emailVerificationToken,
                'email_verified_at' => null,
            ]);

            // 2. Create user_profile
            $idApp = 'USR' . str_pad($user->id, 6, '0', STR_PAD_LEFT);
            DB::table('user_profiles')->insert([
                'user_id' => $user->id,
                'full_name' => $request->full_name,
                'id_app' => $idApp,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Tạo URL xác nhận
            $verificationUrl = url('/auth/verify-email/' . $emailVerificationToken);

            // Gửi email xác nhận
            Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));

            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->user_role,
                'id_app' => $idApp,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công! Vui lòng kiểm tra email để xác nhận tài khoản.',
            ]);
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại',
            ]);
        }
    }

    /**
     * Xác nhận email thông qua token
     */
    public function verifyEmail($token)
    {
        try {
            $user = User::where('email_verification_token', $token)
                ->whereNull('email_verified_at')
                ->first();

            if (! $user) {
                return redirect('/auth/register')->with('error', 'Link xác nhận không hợp lệ hoặc đã hết hạn.');
            }

            // Xác nhận email
            $user->email_verified_at = now();
            $user->email_verification_token = null;
            $user->save();

            Log::info('Email verified successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return redirect('/auth/login')->with('success', 'Email đã được xác nhận thành công! Bạn có thể đăng nhập ngay.');
        } catch (\Exception $e) {
            Log::error('Email verification error: ' . $e->getMessage());

            return redirect('/auth/register')->with('error', 'Có lỗi xảy ra khi xác nhận email.');
        }
    }

    /**
     * Gửi lại email xác nhận
     */
    public function resendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        try {
            $user = User::where('email', $request->email)
                ->whereNull('email_verified_at')
                ->first();

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email không tồn tại hoặc đã được xác nhận.',
                ]);
            }

            // Tạo token mới
            $emailVerificationToken = Str::random(60);
            $user->email_verification_token = $emailVerificationToken;
            $user->save();

            // Tạo URL xác nhận mới
            $verificationUrl = url('/auth/verify-email/' . $emailVerificationToken);

            // Gửi email
            Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));

            return response()->json([
                'success' => true,
                'message' => 'Email xác nhận đã được gửi lại.',
            ]);
        } catch (\Exception $e) {
            Log::error('Resend verification email error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi email.',
            ]);
        }
    }

    /**
     * Hiển thị trang check email
     */
    public function showCheckEmailPage(Request $request)
    {
        $email = $request->get('email');

        return view('auth.check-email', compact('email'));
    }

    // Google OAuth methods
    public function redirectToGoogle(Request $request)
    {
        $linkToken = $request->get('link_token');

        if ($linkToken) {
            // Lưu token vào session để callback có thể sử dụng
            Session::put('google_link_token', $linkToken);
            Log::info('Google redirect with link token', ['token' => $linkToken]);
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Temporarily disable SSL verification for development
            if (config('app.env') === 'local') {
                $originalVerifyPeer = ini_get('openssl.cafile');
                $originalCurlCA = ini_get('curl.cainfo');

                // Temporarily disable SSL verification
                ini_set('openssl.cafile', '');
                ini_set('curl.cainfo', '');

                $googleUser = Socialite::driver('google')->user();

                // Restore original settings
                ini_set('openssl.cafile', $originalVerifyPeer);
                ini_set('curl.cainfo', $originalCurlCA);
            } else {
                $googleUser = Socialite::driver('google')->user();
            }

            // Start session manually if not started
            if (! Session::isStarted()) {
                Session::start();
            }

            // Kiểm tra link token từ session
            $linkToken = Session::get('google_link_token');
            $action = 'login'; // Default

            if ($linkToken) {
                // Lấy thông tin từ cache
                $cacheData = DB::table('cache')
                    ->where('key', "google_link_token_{$linkToken}")
                    ->first();

                if ($cacheData && $cacheData->expiration > time()) {
                    $linkData = json_decode($cacheData->value, true);
                    if ($linkData && $linkData['action'] === 'link') {
                        $action = 'link';
                        // Set user_id vào session từ cache data
                        Session::put('user_id', $linkData['user_id']);
                    }
                }

                // Xóa token sau khi sử dụng
                Session::forget('google_link_token');
                DB::table('cache')->where('key', "google_link_token_{$linkToken}")->delete();
            }

            // Debug thông tin Google user
            $this->debugGoogleUser($googleUser, 'handleGoogleCallback');

            Log::info('Google callback received', [
                'action' => $action,
                'google_email' => $googleUser->getEmail(),
                'session_user_id' => Session::get('user_id'),
                'session_username' => Session::get('username'),
                'session_started' => Session::isStarted(),
                'session_id' => Session::getId(),
                'link_token' => $linkToken,
            ]);

            if ($action === 'link') {
                // Handle linking existing account
                return $this->handleGoogleLinkCallback($googleUser);
            }

            // Kiểm tra Google ID đã được sử dụng chưa
            $conflictCheck = $this->checkGoogleAccountConflicts($googleUser->getId(), $googleUser->getEmail());
            if ($conflictCheck['conflict']) {
                if ($conflictCheck['type'] === 'google_id') {
                    // Nếu Google ID đã tồn tại, đăng nhập bằng tài khoản đó
                    Auth::login($conflictCheck['existing_user']);
                    Session::put('user_id', $conflictCheck['existing_user']->id);
                    Session::put('username', $conflictCheck['existing_user']->name);
                    Session::put('role', $conflictCheck['existing_user']->user_role);

                    return redirect($this->getRedirectUrlByRole($conflictCheck['existing_user']->user_role));
                } else {
                    // Nếu Google email trùng lặp
                    Log::warning('Google email conflict detected', [
                        'google_email' => $googleUser->getEmail(),
                        'existing_user' => $conflictCheck['existing_user']->id,
                    ]);

                    return redirect()->route('auth.login')
                        ->with('error', $conflictCheck['message']);
                }
            }

            // Check if user already exists by email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Update Google ID if not set
                if (! $user->google_id) {
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update([
                            'google_id' => $googleUser->getId(),
                            'google_email' => $googleUser->getEmail(),
                        ]);
                }
                
                // Always sync Google avatar to user_profiles
                $googleAvatar = $googleUser->getAvatar();
                if ($googleAvatar && $user->profile) {
                    // Only update if avatar is a Google URL or user has no avatar
                    $currentAvatar = $user->profile->avatar;
                    if (!$currentAvatar || filter_var($currentAvatar, FILTER_VALIDATE_URL)) {
                        DB::table('user_profiles')
                            ->where('user_id', $user->id)
                            ->update([
                                'avatar' => $googleAvatar,
                                'updated_at' => now(),
                            ]);
                        
                        Log::info('Google avatar synced for existing user', [
                            'user_id' => $user->id,
                            'avatar_url' => substr($googleAvatar, 0, 50) . '...',
                        ]);
                    }
                }
            } else {
                // Kiểm tra dữ liệu Google trước khi tạo user
                $googleName = $googleUser->getName();
                $googleEmail = $googleUser->getEmail();
                $googleId = $googleUser->getId();
                $googleAvatar = $googleUser->getAvatar();

                // Validate dữ liệu Google
                if (empty($googleName) || empty($googleEmail) || empty($googleId)) {
                    Log::error('Google user data incomplete', [
                        'name' => $googleName,
                        'email' => $googleEmail,
                        'google_id' => $googleId,
                    ]);

                    return redirect()->route('auth.login')
                        ->with('error', 'Thông tin tài khoản Google không đầy đủ. Vui lòng thử lại.');
                }

                try {
                    // 1. Create new user
                    $userId = DB::table('users')->insertGetId([
                        'name' => $googleName,
                        'email' => $googleEmail,
                        'google_id' => $googleId,
                        'google_email' => $googleEmail,
                        'password' => Hash::make(Str::random(32)),
                        'user_role' => 'participant', // Default role for new users
                        'status' => 'active',
                        'email_verified_at' => now(), // Google account is verified
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // 2. Create user_profile with full_name and avatar
                    $idApp = 'GGL' . str_pad($userId, 6, '0', STR_PAD_LEFT);
                    DB::table('user_profiles')->insert([
                        'user_id' => $userId,
                        'full_name' => $googleName,
                        'avatar' => $googleAvatar,
                        'id_app' => $idApp,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::info('New Google user created', [
                        'user_id' => $userId,
                        'email' => $googleEmail,
                        'google_id' => $googleId,
                        'id_app' => $idApp,
                    ]);

                    // Get the created user
                    $user = User::find($userId);
                } catch (\Exception $e) {
                    Log::error('Failed to create Google user', [
                        'error' => $e->getMessage(),
                        'email' => $googleEmail,
                        'google_id' => $googleId,
                    ]);

                    return redirect()->route('auth.login')
                        ->with('error', 'Không thể tạo tài khoản từ Google. Vui lòng thử lại.');
                }

                if (! $user) {
                    Log::error('User not found after creation', ['email' => $googleEmail]);

                    return redirect()->route('auth.login')
                        ->with('error', 'Có lỗi xảy ra khi tạo tài khoản. Vui lòng thử lại.');
                }
            }

            Auth::login($user);

            // Also set session for compatibility with existing system
            Session::put('user_id', $user->id);
            Session::put('username', $user->name);
            Session::put('role', $user->user_role);

            return redirect($this->getRedirectUrlByRole($user->user_role));
        } catch (\Exception $e) {
            Log::error('Google OAuth error: ' . $e->getMessage());

            return redirect()->route('auth.login')->with('error', 'Đăng nhập với Google thất bại');
        }
    }

    /**
     * Đăng xuất (JSON response)
     */
    public function logoutApi(Request $request): JsonResponse
    {
        $username = Session::get('username');

        // Xóa session
        Session::flush();

        if ($username) {
            Log::info('User logged out', ['username' => $username]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công',
            'redirect_url' => route('auth.login'),
        ]);
    }

    /**
     * Đăng xuất (Form POST)
     */
    public function logout(Request $request)
    {
        $username = Session::get('username');

        // Xóa session
        Session::flush();

        if ($username) {
            Log::info('User logged out', ['username' => $username]);
        }

        // Không hiển thị thông báo khi đăng xuất
        return redirect()->route('home');
    }

    /**
     * Kiểm tra trạng thái đăng nhập
     */
    public function checkAuth(): JsonResponse
    {
        $userId = Session::get('user_id');

        if ($userId) {
            $user = $this->authService->getUserById($userId);

            if ($user && $user->isActive()) {
                return response()->json([
                    'authenticated' => true,
                    'user' => [
                        'id' => $user->user_id,
                        'username' => $user->username,
                        'role' => $user->user_role,
                        'full_name' => $user->full_name,
                    ],
                ]);
            }
        }

        return response()->json(['authenticated' => false]);
    }

    /**
     * Lấy URL chuyển hướng theo role
     */
    private function getRedirectUrlByRole(?string $role): string
    {
        return match ($role) {
            'super_admin' => '/dashboard',
            'admin' => '/dashboard',
            'participant' => '/posts',  // Participant -> bảng tin posts
            default => '/posts'         // Default -> posts
        };
    }

    // ========== FORGOT PASSWORD METHODS ==========

    /**
     * Hiển thị form quên mật khẩu
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Xử lý yêu cầu quên mật khẩu
     */
    public function processForgotPassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ], [
                'email.required' => 'Email là bắt buộc',
                'email.email' => 'Email không hợp lệ',
                'email.exists' => 'Email không tồn tại trong hệ thống',
            ]);

            $user = User::where('email', $request->email)->first();

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email không tồn tại trong hệ thống',
                ]);
            }

            // Tạo token reset password
            $token = Str::random(64);

            // Xóa token cũ (nếu có)
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            // Tạo token mới
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now(),
                'expires_at' => now()->addMinutes(60), // Token hết hạn sau 60 phút
            ]);

            // Tạo URL reset password
            $resetUrl = url('/auth/reset-password?token=' . $token . '&email=' . urlencode($request->email));

            // Gửi email
            Mail::to($user->email)->send(new ForgotPasswordEmail($user, $resetUrl, $token));

            Log::info('Password reset email sent', [
                'email' => $user->email,
                'token' => substr($token, 0, 10) . '...',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Link đặt lại mật khẩu đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư.',
            ]);
        } catch (\Exception $e) {
            Log::error('Forgot password error: ' . $e->getMessage(), [
                'email' => $request->email ?? 'N/A',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi email. Vui lòng thử lại.',
            ]);
        }
    }

    /**
     * Hiển thị form reset password
     */
    public function showResetPasswordForm(Request $request)
    {
        $token = $request->get('token');
        $email = $request->get('email');

        if (! $token || ! $email) {
            return redirect()->route('auth.login')->with('error', 'Link không hợp lệ');
        }

        // Kiểm tra token có tồn tại và chưa hết hạn
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (! $resetRecord) {
            return redirect()->route('auth.login')->with('error', 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn');
        }

        return view('auth.reset-password', compact('token', 'email'));
    }

    /**
     * Xử lý đặt lại mật khẩu
     */
    public function processResetPassword(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'email' => 'required|email|exists:users,email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).*$/',
                    'confirmed',
                ],
            ], [
                'token.required' => 'Token không hợp lệ',
                'email.required' => 'Email là bắt buộc',
                'email.email' => 'Email không hợp lệ',
                'email.exists' => 'Email không tồn tại',
                'password.required' => 'Mật khẩu là bắt buộc',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ thường, 1 chữ hoa và 1 số',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ]);
            }

            // Kiểm tra token có tồn tại và chưa hết hạn
            $resetRecord = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->where('expires_at', '>', now())
                ->first();

            if (! $resetRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token không hợp lệ hoặc đã hết hạn',
                ]);
            }

            // Cập nhật mật khẩu
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            // Xóa token đã sử dụng
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            Log::info('Password reset successful', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mật khẩu đã được đặt lại thành công. Bạn có thể đăng nhập với mật khẩu mới.',
            ]);
        } catch (\Exception $e) {
            Log::error('Reset password error: ' . $e->getMessage(), [
                'email' => $request->email ?? 'N/A',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đặt lại mật khẩu. Vui lòng thử lại.',
            ]);
        }
    }

    /**
     * Liên kết tài khoản Google với tài khoản hiện tại
     */
    public function linkGoogle()
    {
        $userId = Session::get('user_id');
        $username = Session::get('username');

        Log::info('Google link initiated', [
            'user_id' => $userId,
            'username' => $username,
            'session_data' => Session::all(),
        ]);

        if (! $userId) {
            Log::warning('Google link attempt without user session');

            return redirect()->route('auth.login')
                ->with('error', 'Vui lòng đăng nhập trước khi liên kết Google.');
        }

        // Lưu thông tin link action vào database thay vì session
        $linkToken = Str::random(32);
        DB::table('cache')->insertOrIgnore([
            'key' => "google_link_token_{$linkToken}",
            'value' => json_encode([
                'user_id' => $userId,
                'action' => 'link',
                'created_at' => time(),
            ]),
            'expiration' => time() + 600, // Hết hạn sau 10 phút
        ]);

        // Redirect với token
        return redirect()->route('auth.google', ['link_token' => $linkToken]);
    }

    /**
     * Xử lý callback khi liên kết Google
     */
    public function handleGoogleLinkCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $userId = Session::get('user_id');

            if (! $userId) {
                return redirect()->route('auth.login')
                    ->with('error', 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.');
            }

            $user = User::find($userId);
            if (! $user) {
                return redirect()->route('auth.login')
                    ->with('error', 'Không tìm thấy tài khoản.');
            }

            // Sử dụng helper method để kiểm tra xung đột
            $conflictCheck = $this->checkGoogleAccountConflicts($googleUser->getId(), $googleUser->getEmail(), $user->id);
            if ($conflictCheck['conflict']) {
                return redirect()->route('profile.edit')
                    ->with('error', $conflictCheck['message']);
            }

            // Kiểm tra nếu user đã có Google ID khác
            if ($user->google_id && $user->google_id !== $googleUser->getId()) {
                return redirect()->route('profile.edit')
                    ->with('error', 'Tài khoản của bạn đã được liên kết với Google ID khác.');
            }            // Cập nhật Google ID cho user hiện tại
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'google_id' => $googleUser->getId(),
                    'google_email' => $googleUser->getEmail(),
                ]);
            
            // Sync Google avatar to user_profiles
            $googleAvatar = $googleUser->getAvatar();
            if ($googleAvatar && $user->profile) {
                $currentAvatar = $user->profile->avatar;
                // Only update if user has no avatar or current is a URL (Google avatar)
                if (!$currentAvatar || filter_var($currentAvatar, FILTER_VALIDATE_URL)) {
                    DB::table('user_profiles')
                        ->where('user_id', $user->id)
                        ->update([
                            'avatar' => $googleAvatar,
                            'updated_at' => now(),
                        ]);
                }
            }

            Log::info('Google account linked', [
                'user_id' => $user->id,
                'google_id' => $googleUser->getId(),
            ]);

            return redirect()->route('profile.edit')
                ->with('success', 'Liên kết tài khoản Google thành công!');
        } catch (\Exception $e) {
            Log::error('Google link error: ' . $e->getMessage());

            return redirect()->route('profile.edit')
                ->with('error', 'Có lỗi xảy ra khi liên kết tài khoản Google.');
        }
    }

    /**
     * Hủy liên kết tài khoản Google
     */
    public function unlinkGoogle()
    {
        try {
            $userId = Session::get('user_id');

            if (! $userId) {
                return redirect()->route('auth.login')
                    ->with('error', 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.');
            }

            $user = User::find($userId);
            if (! $user) {
                return redirect()->route('auth.login')
                    ->with('error', 'Không tìm thấy tài khoản.');
            }

            if (! $user->google_id) {
                return redirect()->route('profile.edit')
                    ->with('error', 'Tài khoản chưa được liên kết với Google.');
            }

            // Xóa Google ID
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'google_id' => null,
                    'google_email' => null,
                ]);

            Log::info('Google account unlinked', [
                'user_id' => $user->id,
            ]);

            return redirect()->route('profile.edit')
                ->with('success', 'Hủy liên kết tài khoản Google thành công!');
        } catch (\Exception $e) {
            Log::error('Google unlink error: ' . $e->getMessage());

            return redirect()->route('profile.edit')
                ->with('error', 'Có lỗi xảy ra khi hủy liên kết tài khoản Google.');
        }
    }
}
