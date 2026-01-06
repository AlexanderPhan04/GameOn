<?php

use App\Http\Controllers\Admin\HonorManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\GameManagementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HonorController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TournamentManagementController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\Admin\MarketplaceController as AdminMarketplaceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', SearchController::class)->name('search');
Route::get('/search/view', [SearchController::class, 'view'])->name('search.view');

// Maintenance routes
Route::get('/maintenance-test', function () {
    return view('maintenance', [
        'error' => 'Test maintenance mode',
        'timestamp' => now()->format('d/m/Y H:i:s'),
    ]);
})->name('maintenance.test');

Route::get('/system-status', [MaintenanceController::class, 'status'])->name('system.status');
Route::get('/api/check-status', [MaintenanceController::class, 'checkStatus'])->name('api.check.status');

// Language routes - without middleware to avoid database issues
Route::post('/language/switch', [LanguageController::class, 'switch'])->name('language.switch');
Route::get('/language/current', [LanguageController::class, 'current'])->name('language.current');

Route::resource('tournaments', TournamentController::class);

// Authentication routes - chuyển đổi từ EsportsManager
Route::prefix('auth')->name('auth.')->group(function () {
    // GET routes for forms
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

    // POST routes for processing
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/logout-api', [AuthController::class, 'logoutApi'])->name('logout.api');

    // Email verification routes
    Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email');
    Route::post('/resend-verification', [AuthController::class, 'resendVerificationEmail'])->name('resend.verification');
    Route::get('/check-email', [AuthController::class, 'showCheckEmailPage'])->name('check.email');

    // Forgot Password routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot.password');
    Route::post('/forgot-password', [AuthController::class, 'processForgotPassword'])->name('forgot.password.process');
    Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('reset.password');
    Route::post('/reset-password', [AuthController::class, 'processResetPassword'])->name('reset.password.process');

    // Google OAuth routes
    Route::get('/google', [AuthController::class, 'redirectToGoogle'])->name('google');
    Route::get('/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

    // Debug route for development (remove in production)
    if (config('app.env') === 'local') {
        Route::get('/google/debug', function () {
            return response()->json([
                'users_with_google' => \App\Models\User::whereNotNull('google_id')
                    ->select('id', 'name', 'email', 'google_id', 'google_email', 'created_at')
                    ->get(),
                'total_users' => \App\Models\User::count(),
                'total_google_users' => \App\Models\User::whereNotNull('google_id')->count(),
            ]);
        })->name('google.debug');
    }

    // Google Link/Unlink routes (for authenticated users)
    Route::middleware(['auth.session'])->group(function () {
        Route::get('/google/link', [AuthController::class, 'linkGoogle'])->name('google.link');
        Route::get('/google/unlink', [AuthController::class, 'unlinkGoogle'])->name('google.unlink');
    });

    // API routes
    Route::get('/check', [AuthController::class, 'checkAuth'])->name('check');
});

// Role-based dashboard routes
Route::middleware(['auth.session'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/recent-users', [HomeController::class, 'getRecentUsers'])->name('dashboard.recent-users');

    // Admin dashboard routes
    Route::get('/admin/dashboard', [HomeController::class, 'dashboard'])->name('admin.dashboard');

    // Teams routes - for participants and admins
    Route::middleware(['check.participant.role'])->group(function () {
        Route::resource('teams', TeamController::class);
        Route::post('/teams/{team}/join', [TeamController::class, 'join'])->name('teams.join');
        Route::post('/teams/{team}/leave', [TeamController::class, 'leave'])->name('teams.leave');
        Route::post('/teams/{team}/transfer-captain', [TeamController::class, 'transferCaptain'])->name('teams.transfer-captain');
        Route::post('/teams/{team}/kick-member', [TeamController::class, 'kickMember'])->name('teams.kick-member');
        Route::post('/teams/{team}/invite-member', [TeamController::class, 'inviteMember'])->name('teams.invite-member');
    });
});

// Profile routes - outside middleware to ensure routes are always available
Route::middleware(['auth.session', 'track.login'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    Route::get('/search-users', [ProfileController::class, 'searchUsers'])->name('search-users');
    Route::get('/user/{id}', [ProfileController::class, 'showUser'])->name('show-user');
});

// Game Management Routes (Admin only)
Route::prefix('admin')->name('admin.')->middleware(['auth.session'])->group(function () {
    Route::resource('games', GameManagementController::class);
    Route::get('games-api', [GameManagementController::class, 'getGames'])->name('games.api');
});

// Tournament Management Routes (Admin only)
Route::prefix('admin')->name('admin.')->middleware(['auth.session'])->group(function () {
    Route::resource('tournaments', TournamentManagementController::class);
    Route::get('tournaments-api', [TournamentManagementController::class, 'getTournaments'])->name('tournaments.api');
});

// User Management Routes (Admin only)
Route::prefix('admin')->name('admin.')->middleware(['auth.session'])->group(function () {
    Route::resource('users', UserManagementController::class);
    Route::patch('users/{user}/status', [UserManagementController::class, 'updateStatus'])->name('users.update-status');
    Route::post('users/bulk-update', [UserManagementController::class, 'bulkUpdate'])->name('users.bulk-update');
    Route::get('users-export', [UserManagementController::class, 'export'])->name('users.export');
});

// Team Management Routes (Admin only)
Route::prefix('admin')->name('admin.')->middleware(['auth.session'])->group(function () {
    Route::resource('teams', \App\Http\Controllers\Admin\TeamManagementController::class);
    Route::patch('teams/{team}/status', [\App\Http\Controllers\Admin\TeamManagementController::class, 'updateStatus'])->name('teams.update-status');
    Route::post('teams/bulk-update', [\App\Http\Controllers\Admin\TeamManagementController::class, 'bulkUpdate'])->name('teams.bulk-update');
    Route::get('teams-export', [\App\Http\Controllers\Admin\TeamManagementController::class, 'export'])->name('teams.export');
});

// System Management Routes (Super Admin only)
Route::prefix('admin')->name('admin.')->middleware(['auth.session'])->group(function () {
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('settings', [\App\Http\Controllers\Admin\SystemController::class, 'settings'])->name('settings');
        Route::post('settings', [\App\Http\Controllers\Admin\SystemController::class, 'updateSettings'])->name('update-settings');
        Route::post('update-theme', [\App\Http\Controllers\Admin\SystemController::class, 'updateTheme'])->name('update-theme');
        Route::get('logs', [\App\Http\Controllers\Admin\SystemController::class, 'logs'])->name('logs');
        Route::get('backups', [\App\Http\Controllers\Admin\SystemController::class, 'listBackups'])->name('backups');
        Route::post('create-backup', [\App\Http\Controllers\Admin\SystemController::class, 'createBackup'])->name('create-backup');
        Route::get('download-backup/{filename}', [\App\Http\Controllers\Admin\SystemController::class, 'downloadBackup'])->name('download-backup');
        Route::get('analytics', [\App\Http\Controllers\Admin\SystemController::class, 'analytics'])->name('analytics');
        Route::post('clear-cache', [\App\Http\Controllers\Admin\SystemController::class, 'clearCache'])->name('clear-cache');
    });
});

// Compatibility routes (redirect old URLs)
Route::get('/login', function () {
    return redirect()->route('auth.login');
});

Route::get('/register', function () {
    return redirect()->route('auth.register');
});

// Temporary test route
Route::get('/register', function () {
    return redirect()->route('auth.register');
});

// Chat routes - Global messaging system
Route::middleware(['auth.session'])->prefix('chat')->name('chat.')->group(function () {
    // Main chat interface
    Route::get('/', [ChatController::class, 'index'])->name('index');

    // Conversation routes
    Route::get('/conversation/{conversation}', [ChatController::class, 'show'])->name('show');
    Route::post('/start-conversation', [ChatController::class, 'startConversation'])->name('start');
    Route::post('/create-group', [ChatController::class, 'createGroup'])->name('create-group');
    Route::delete('/conversation/{conversation}/delete', [ChatController::class, 'deleteConversation'])->name('delete');
    Route::post('/conversation/{conversation}/clear-history', [ChatController::class, 'clearHistory'])->name('clear-history');
    Route::post('/conversation/{conversation}/send', [ChatController::class, 'sendMessage'])->name('send');
    Route::get('/conversation/{conversation}/messages', [ChatController::class, 'getMessages'])->name('messages');
    Route::post('/conversation/{conversation}/mark-read', [ChatController::class, 'markAsRead'])->name('mark-read');
    Route::delete('/conversation/{conversation}/leave', [ChatController::class, 'leaveConversation'])->name('leave');

    // Message management
    Route::delete('/message/{message}', [ChatController::class, 'deleteMessage'])->name('message.delete');
    Route::put('/message/{message}', [ChatController::class, 'editMessage'])->name('message.edit');
    Route::post('/message/{message}/react', [ChatController::class, 'addReaction'])->name('message.react');
    Route::post('/message/{message}/toggle-reaction', [ChatController::class, 'toggleReaction'])->name('toggle-reaction');
    Route::post('/message/{message}/report', [ChatController::class, 'reportMessage'])->name('message.report');

    // User interactions
    Route::get('/search-users', [ChatController::class, 'searchUsers'])->name('search-users');
    Route::post('/conversation/{conversation}/block', [ChatController::class, 'toggleBlock'])->name('block');

    // Real-time features
    Route::post('/conversation/{conversation}/typing', [ChatController::class, 'updateTypingStatus'])->name('typing');
    Route::get('/conversation/{conversation}/typing-users', [ChatController::class, 'getTypingUsers'])->name('typing-users');
    Route::get('/online-count', [ChatController::class, 'getOnlineUsersCount'])->name('online-count');
});

// API routes for real-time features
Route::prefix('api')->middleware(['auth.session'])->group(function () {
    Route::post('/user/heartbeat', [ChatController::class, 'heartbeat'])->name('api.user.heartbeat');
    Route::get('/conversation/{conversation}/participants/status', [ChatController::class, 'getParticipantsStatus'])->name('api.conversation.participants.status');
});

// Posts routes (basic placeholder)
Route::middleware(['auth.session'])->group(function () {
    Route::get('/posts', [PostsController::class, 'index'])->name('posts.index');
    Route::post('/posts', [PostsController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostsController::class, 'destroy'])->name('posts.destroy');
    Route::put('/posts/{post}', [PostsController::class, 'update'])->name('posts.update');
    Route::post('/posts/{post}/like', [PostsController::class, 'toggleLike'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostsController::class, 'comment'])->name('posts.comment');
    Route::post('/posts/{post}/react', [PostsController::class, 'react'])->name('posts.react');
    Route::get('/posts/{post}/reactions', [PostsController::class, 'listReactions'])->name('posts.reactions');
    Route::get('/posts/{post}/counters', [PostsController::class, 'counters'])->name('posts.counters');
    Route::get('/posts/{post}/shares', [PostsController::class, 'listShares'])->name('posts.shares');
    Route::get('/posts/{post}/comments', [PostsController::class, 'getComments'])->name('posts.comments');
    Route::post('/comments/{comment}/like', [PostsController::class, 'toggleCommentLike'])->name('comments.like');
    Route::post('/comments/{comment}/react', [PostsController::class, 'reactComment'])->name('comments.react');
    Route::put('/comments/{comment}', [PostsController::class, 'updateComment'])->name('comments.update');
    Route::delete('/comments/{comment}', [PostsController::class, 'deleteComment'])->name('comments.delete');
});

// Honor System Routes
Route::prefix('honor')->name('honor.')->group(function () {
    Route::get('/', [HonorController::class, 'index'])->name('index');
    Route::get('/{honorEvent}', [HonorController::class, 'show'])->name('show');
    Route::post('/{honorEvent}/vote', [HonorController::class, 'vote'])->name('vote');
    Route::get('/{honorEvent}/results', [HonorController::class, 'results'])->name('results');
});

// Admin Honor Management Routes
Route::prefix('admin/honor')->name('admin.honor.')->middleware(['auth.session'])->group(function () {
    Route::get('/', [HonorManagementController::class, 'index'])->name('index');
    Route::get('/create', [HonorManagementController::class, 'create'])->name('create');
    Route::post('/', [HonorManagementController::class, 'store'])->name('store');
    Route::get('/{honorEvent}', [HonorManagementController::class, 'show'])->name('show');
    Route::get('/{honorEvent}/edit', [HonorManagementController::class, 'edit'])->name('edit');
    Route::put('/{honorEvent}', [HonorManagementController::class, 'update'])->name('update');
    Route::patch('/{honorEvent}/toggle', [HonorManagementController::class, 'toggleStatus'])->name('toggle');
    Route::delete('/{honorEvent}/reset', [HonorManagementController::class, 'resetVotes'])->name('reset');
    Route::delete('/{honorEvent}', [HonorManagementController::class, 'destroy'])->name('destroy');
});

// Admin Management Routes (Super Admin only)
Route::prefix('admin/admins')->name('admin.admins.')->middleware(['auth.session'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AdminInvitationController::class, 'index'])->name('index');
    Route::get('/invite', [\App\Http\Controllers\Admin\AdminInvitationController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Admin\AdminInvitationController::class, 'store'])->name('store');
    Route::get('/{user}/edit-permissions', [\App\Http\Controllers\Admin\AdminInvitationController::class, 'editPermissions'])->name('edit-permissions');
    Route::put('/{user}/update-permissions', [\App\Http\Controllers\Admin\AdminInvitationController::class, 'updatePermissions'])->name('update-permissions');
    Route::delete('/{user}/revoke', [\App\Http\Controllers\Admin\AdminInvitationController::class, 'revokeAdmin'])->name('revoke');
    Route::delete('/invitation/{invitation}/cancel', [\App\Http\Controllers\Admin\AdminInvitationController::class, 'cancelInvitation'])->name('cancel-invitation');
    Route::post('/invitation/{invitation}/resend', [\App\Http\Controllers\Admin\AdminInvitationController::class, 'resendInvitation'])->name('resend-invitation');
});

// Public routes for Admin Invitation (no auth required)
Route::get('/admin-invitation/{token}/accept', [\App\Http\Controllers\Admin\AdminInvitationController::class, 'accept'])->name('admin.invitation.accept');
Route::get('/admin-invitation/{token}/reject', [\App\Http\Controllers\Admin\AdminInvitationController::class, 'reject'])->name('admin.invitation.reject');

// Admin Marketplace Management Routes
Route::prefix('admin/marketplace')->name('admin.marketplace.')->middleware(['auth.session'])->group(function () {
    Route::get('/', [AdminMarketplaceController::class, 'index'])->name('index');
    Route::get('/create', [AdminMarketplaceController::class, 'create'])->name('create');
    Route::post('/', [AdminMarketplaceController::class, 'store'])->name('store');
    Route::get('/{id}', [AdminMarketplaceController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [AdminMarketplaceController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AdminMarketplaceController::class, 'update'])->name('update');
    Route::delete('/{id}', [AdminMarketplaceController::class, 'destroy'])->name('destroy');
    Route::patch('/{id}/toggle-status', [AdminMarketplaceController::class, 'toggleStatus'])->name('toggleStatus');
});

// Payment Routes - PayOS Integration
Route::prefix('payment')->name('payment.')->group(function () {
    // Tạo link thanh toán PayOS
    Route::post('/payos/create', [PaymentController::class, 'createPayment'])->name('payos.create');

    // Callback từ PayOS sau khi thanh toán thành công
    Route::get('/payos/success', [PaymentController::class, 'handleSuccess'])->name('success');

    // Callback từ PayOS khi hủy thanh toán
    Route::get('/payos/cancel', [PaymentController::class, 'handleCancel'])->name('cancel');

    // Webhook từ PayOS (IPN)
    Route::post('/payos/webhook', [PaymentController::class, 'webhook'])->name('payos.webhook');
});

// Marketplace Routes
Route::prefix('marketplace')->name('marketplace.')->group(function () {
    Route::get('/', [MarketplaceController::class, 'index'])->name('index');
    Route::get('/product', fn() => redirect()->route('marketplace.index')); // Redirect if no slug provided
    Route::get('/product/{product}', [MarketplaceController::class, 'show'])->name('show');
    Route::post('/cart/{id}', [MarketplaceController::class, 'addToCart'])->name('addToCart')->middleware('auth.session');
    Route::get('/cart', [MarketplaceController::class, 'cart'])->name('cart');
    Route::patch('/cart/{id}', [MarketplaceController::class, 'updateCartQuantity'])->name('updateCartQuantity')->middleware('auth.session');
    Route::delete('/cart/{id}', [MarketplaceController::class, 'removeFromCart'])->name('removeFromCart')->middleware('auth.session');
    Route::get('/checkout', [MarketplaceController::class, 'checkout'])->name('checkout')->middleware('auth.session');
    Route::post('/process-payment', [MarketplaceController::class, 'processPayment'])->name('processPayment')->middleware('auth.session');
    Route::get('/inventory', [MarketplaceController::class, 'inventory'])->name('inventory')->middleware('auth.session');
    Route::post('/inventory/{id}/equip', [MarketplaceController::class, 'equipItem'])->name('equipItem')->middleware('auth.session');
    Route::post('/donate/{userId}', [MarketplaceController::class, 'donate'])->name('donate')->middleware('auth.session');
    
    // Order History & Invoice
    Route::get('/orders', [MarketplaceController::class, 'orderHistory'])->name('orders')->middleware('auth.session');
    Route::get('/orders/{orderCode}', [MarketplaceController::class, 'orderDetail'])->name('orderDetail')->middleware('auth.session');
    Route::get('/orders/{orderCode}/invoice', [MarketplaceController::class, 'downloadInvoice'])->name('invoice')->middleware('auth.session');
});
