<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Services\GoogleAuthService;
use App\Services\PasswordResetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

/**
 * AuthController - HTTP layer for authentication
 * Business logic delegated to AuthService, GoogleAuthService, PasswordResetService
 * Refactored for proper MVC architecture
 */
class AuthController extends Controller
{
    protected AuthService $authService;
    protected GoogleAuthService $googleAuthService;
    protected PasswordResetService $passwordResetService;

    public function __construct(
        AuthService $authService,
        GoogleAuthService $googleAuthService,
        PasswordResetService $passwordResetService
    ) {
        $this->authService = $authService;
        $this->googleAuthService = $googleAuthService;
        $this->passwordResetService = $passwordResetService;
    }

    // ========== LOGIN / REGISTER ==========

    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        if (Session::has('user_id')) {
            return redirect('/posts');
        }

        return view('auth.auth', ['mode' => 'login']);
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu là bắt buộc',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $result = $this->authService->login(
            $request->email,
            $request->password,
            $request->filled('remember')
        );

        if ($result['success']) {
            $request->session()->regenerate();
            
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'user' => [
                    'id' => $result['user']->id,
                    'name' => $result['user']->name,
                    'email' => $result['user']->email,
                    'role' => $result['user']->user_role,
                ],
                'redirect_url' => $result['redirect_url'],
            ]);
        }

        $response = [
            'success' => false,
            'message' => $result['message'],
        ];

        if (isset($result['requires_verification'])) {
            $response['requires_verification'] = true;
            $response['email'] = $result['email'];
        }

        return response()->json($response, 400);
    }

    /**
     * Hiển thị form đăng ký
     */
    public function showRegisterForm()
    {
        return view('auth.auth', ['mode' => 'register']);
    }

    /**
     * Xử lý đăng ký
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

        $result = $this->authService->register($request->only([
            'full_name', 'email', 'password'
        ]));

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
        ]);
    }

    // ========== EMAIL VERIFICATION ==========

    /**
     * Xác nhận email
     */
    public function verifyEmail($token)
    {
        $result = $this->authService->verifyEmail($token);

        if ($result['success']) {
            return redirect('/auth/login')->with('success', $result['message']);
        }

        return redirect('/auth/register')->with('error', $result['message']);
    }

    /**
     * Gửi lại email xác nhận
     */
    public function resendVerificationEmail(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $result = $this->authService->resendVerificationEmail($request->email);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
        ]);
    }

    /**
     * Hiển thị trang check email
     */
    public function showCheckEmailPage(Request $request)
    {
        $email = $request->get('email');
        return view('auth.check-email', compact('email'));
    }

    // ========== GOOGLE OAUTH ==========

    /**
     * Redirect to Google for authentication
     */
    public function redirectToGoogle(Request $request)
    {
        $linkToken = $request->get('link_token');

        if ($linkToken) {
            Session::put('google_link_token', $linkToken);
            Log::info('Google redirect with link token', ['token' => $linkToken]);
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            // Get Google user based on environment
            if (config('app.env') === 'local') {
                $originalVerifyPeer = ini_get('openssl.cafile');
                $originalCurlCA = ini_get('curl.cainfo');
                ini_set('openssl.cafile', '');
                ini_set('curl.cainfo', '');
                
                $googleUser = Socialite::driver('google')->user();
                
                ini_set('openssl.cafile', $originalVerifyPeer);
                ini_set('curl.cainfo', $originalCurlCA);
            } else {
                $googleUser = Socialite::driver('google')->user();
            }

            if (!Session::isStarted()) {
                Session::start();
            }

            // Check if this is a link action
            $linkToken = Session::get('google_link_token');
            if ($linkToken) {
                return $this->handleGoogleLinkCallback($googleUser);
            }

            // Check for conflicts
            $conflictCheck = $this->googleAuthService->checkConflicts(
                $googleUser->getId(),
                $googleUser->getEmail()
            );

            if ($conflictCheck['conflict'] && $conflictCheck['type'] === 'google_id') {
                // Login with existing account
                $user = $conflictCheck['existing_user'];
                \Illuminate\Support\Facades\Auth::login($user);
                $this->authService->setUserSession($user);
                return redirect($this->authService->getRedirectUrlByRole($user->user_role));
            }

            if ($conflictCheck['conflict'] && $conflictCheck['type'] === 'email') {
                return redirect()->route('auth.login')
                    ->with('error', $conflictCheck['message']);
            }

            // Handle callback via service
            $result = $this->googleAuthService->handleCallback($googleUser);

            if ($result['success']) {
                return redirect($result['redirect_url']);
            }

            return redirect()->route('auth.login')->with('error', $result['message']);
        } catch (\Exception $e) {
            Log::error('Google OAuth error: ' . $e->getMessage());
            return redirect()->route('auth.login')->with('error', 'Đăng nhập với Google thất bại');
        }
    }

    /**
     * Handle Google link callback
     */
    protected function handleGoogleLinkCallback($googleUser)
    {
        Session::forget('google_link_token');
        
        $userId = Session::get('user_id');
        if (!$userId) {
            return redirect()->route('auth.login')
                ->with('error', 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('auth.login')
                ->with('error', 'Không tìm thấy tài khoản.');
        }

        $result = $this->googleAuthService->linkAccount($user, $googleUser);

        return redirect()->route('profile.edit')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    /**
     * Link Google account
     */
    public function linkGoogle()
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('auth.login')
                ->with('error', 'Vui lòng đăng nhập trước khi liên kết Google.');
        }

        $linkToken = \Illuminate\Support\Str::random(32);
        \Illuminate\Support\Facades\DB::table('cache')->insertOrIgnore([
            'key' => "google_link_token_{$linkToken}",
            'value' => json_encode([
                'user_id' => $userId,
                'action' => 'link',
                'created_at' => time(),
            ]),
            'expiration' => time() + 600,
        ]);

        return redirect()->route('auth.google', ['link_token' => $linkToken]);
    }

    /**
     * Unlink Google account
     */
    public function unlinkGoogle()
    {
        $userId = Session::get('user_id');
        
        if (!$userId) {
            return redirect()->route('auth.login')
                ->with('error', 'Phiên đăng nhập đã hết hạn.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('auth.login')
                ->with('error', 'Không tìm thấy tài khoản.');
        }

        $result = $this->googleAuthService->unlinkAccount($user);

        return redirect()->route('profile.edit')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    // ========== LOGOUT ==========

    /**
     * Đăng xuất (JSON response)
     */
    public function logoutApi(Request $request): JsonResponse
    {
        $this->authService->logout();

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
        $this->authService->logout();
        return redirect()->route('home');
    }

    /**
     * Kiểm tra trạng thái đăng nhập
     */
    public function checkAuth(): JsonResponse
    {
        $userId = Session::get('user_id');

        if ($userId) {
            $user = User::find($userId);

            if ($user && $user->status === 'active') {
                return response()->json([
                    'authenticated' => true,
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->name,
                        'role' => $user->user_role,
                        'full_name' => $user->full_name,
                    ],
                ]);
            }
        }

        return response()->json(['authenticated' => false]);
    }

    // ========== PASSWORD RESET ==========

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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.exists' => 'Email không tồn tại trong hệ thống',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $result = $this->passwordResetService->sendResetLink($request->email);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
        ]);
    }

    /**
     * Hiển thị form reset password
     */
    public function showResetPasswordForm(Request $request)
    {
        $token = $request->get('token');
        $email = $request->get('email');

        if (!$token || !$email) {
            return redirect()->route('auth.login')->with('error', 'Link không hợp lệ');
        }

        if (!$this->passwordResetService->validateToken($email, $token)) {
            return redirect()->route('auth.login')
                ->with('error', 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn');
        }

        return view('auth.reset-password', compact('token', 'email'));
    }

    /**
     * Xử lý đặt lại mật khẩu
     */
    public function processResetPassword(Request $request): JsonResponse
    {
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

        $result = $this->passwordResetService->resetPassword(
            $request->email,
            $request->token,
            $request->password
        );

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
        ]);
    }
}
