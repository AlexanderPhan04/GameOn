<?php $__env->startSection('title', 'Super Admin Dashboard'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .dashboard-container { background: #000814; min-height: 100vh; }
    .stats-grid { display: grid !important; grid-template-columns: repeat(4, 1fr) !important; gap: 1.25rem !important; }
    @media (max-width: 1280px) { .stats-grid { grid-template-columns: repeat(2, 1fr) !important; } }
    @media (max-width: 640px) { .stats-grid { grid-template-columns: 1fr !important; } }
    
    /* Mobile responsive - full width */
    @media (max-width: 991.98px) {
        .dashboard-container {
            margin-left: 0 !important;
            width: 100% !important;
            padding-left: 0;
            padding-right: 0;
        }
        .dashboard-container .max-w-7xl {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
    
    .welcome-card { background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%); border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 20px; padding: 2rem; position: relative; overflow: hidden; }
    .welcome-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, transparent, #00E5FF, transparent); }
    .avatar-glow { width: 80px; height: 80px; background: linear-gradient(135deg, #000055, #000077); border-radius: 20px; display: flex; align-items: center; justify-content: center; border: 2px solid rgba(0, 229, 255, 0.4); box-shadow: 0 0 30px rgba(0, 229, 255, 0.3); }
    .welcome-title { font-family: 'Rajdhani', sans-serif; font-size: 2rem; font-weight: 700; color: #FFFFFF; }
    .welcome-title span { color: #00E5FF; text-shadow: 0 0 20px rgba(0, 229, 255, 0.5); }
    .time-widget { background: rgba(0, 229, 255, 0.05); border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 16px; padding: 1.5rem 2rem; text-align: center; }
    .time-display { font-family: 'Rajdhani', sans-serif; font-size: 2.5rem; font-weight: 700; color: #00E5FF; text-shadow: 0 0 20px rgba(0, 229, 255, 0.5); }
    .stat-card { background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%); border: 1px solid rgba(0, 229, 255, 0.15); border-radius: 20px; padding: 1.5rem; transition: all 0.4s ease; }
    .stat-card:hover { transform: translateY(-8px); border-color: rgba(0, 229, 255, 0.4); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 30px rgba(0, 229, 255, 0.15); }
    .stat-icon { width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; }
    .stat-icon.cyan { background: linear-gradient(135deg, #000055, #006666); }
    .stat-icon.green { background: linear-gradient(135deg, #004d40, #00796b); }
    .stat-icon.blue { background: linear-gradient(135deg, #1a237e, #3949ab); }
    .stat-icon.orange { background: linear-gradient(135deg, #e65100, #ff9800); }
    .stat-number { font-family: 'Rajdhani', sans-serif; font-size: 3rem; font-weight: 700; color: #FFFFFF; line-height: 1; margin: 0.5rem 0; }
    .stat-label { color: #94a3b8; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 0.5rem; }
    .stat-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
    .stat-badge.up { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    .stat-badge.active { background: rgba(0, 229, 255, 0.15); color: #00E5FF; }
    .stat-link { color: #00E5FF; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.3s ease; }
    .stat-link:hover { color: #FFFFFF; gap: 10px; }
</style>
<style>
    .modern-card { background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%); border: 1px solid rgba(0, 229, 255, 0.15); border-radius: 20px; overflow: hidden; }
    .modern-card-header { background: rgba(0, 229, 255, 0.03); padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0, 229, 255, 0.1); display: flex; align-items: center; justify-content: space-between; }
    .modern-card-title { font-family: 'Rajdhani', sans-serif; font-weight: 700; color: #FFFFFF; font-size: 1.1rem; display: flex; align-items: center; gap: 10px; }
    .modern-card-title i { color: #00E5FF; }
    .btn-neon { background: linear-gradient(135deg, #000055, #000077); color: #00E5FF; border: 1px solid rgba(0, 229, 255, 0.4); padding: 0.5rem 1.25rem; border-radius: 10px; font-size: 0.85rem; font-weight: 600; transition: all 0.3s ease; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
    .btn-neon:hover { background: rgba(0, 229, 255, 0.15); box-shadow: 0 0 20px rgba(0, 229, 255, 0.4); color: #FFFFFF; }
    .btn-ghost { background: transparent; color: #94a3b8; border: 1px solid rgba(0, 229, 255, 0.2); padding: 0.5rem 1rem; border-radius: 10px; font-size: 0.85rem; cursor: pointer; transition: all 0.3s ease; }
    .btn-ghost:hover { background: rgba(0, 229, 255, 0.1); color: #00E5FF; border-color: rgba(0, 229, 255, 0.4); }
    .role-card { background: rgba(0, 229, 255, 0.03); border: 1px solid rgba(0, 229, 255, 0.1); border-radius: 14px; padding: 1rem; transition: all 0.3s ease; }
    .role-card:hover { background: rgba(0, 229, 255, 0.08); border-color: rgba(0, 229, 255, 0.3); transform: translateX(5px); }
    .progress-track { height: 8px; background: rgba(0, 229, 255, 0.1); border-radius: 4px; overflow: hidden; }
    .progress-fill { height: 100%; background: linear-gradient(90deg, #00E5FF, #000077); border-radius: 4px; }
    .quick-action { background: rgba(0, 229, 255, 0.03); border: 1px solid rgba(0, 229, 255, 0.1); border-radius: 14px; padding: 1.25rem; text-align: center; transition: all 0.3s ease; cursor: pointer; text-decoration: none; display: block; }
    .quick-action:hover { background: rgba(0, 229, 255, 0.1); border-color: rgba(0, 229, 255, 0.4); transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0, 229, 255, 0.15); }
    .quick-action-icon { width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.25rem; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        
        <!-- Welcome Card -->
        <div class="welcome-card mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 relative z-10">
                <div class="flex items-center gap-5">
                    <div class="avatar-glow">
                        <i class="fas fa-crown text-3xl text-[#00E5FF]"></i>
                    </div>
                    <div>
                        <h1 class="welcome-title"><?php echo e(__('app.dashboard.welcome')); ?>, <span><?php echo e(Auth::user()->display_name); ?>!</span></h1>
                        <p class="text-[#94a3b8] text-base"><?php echo e(__('app.dashboard.admin_dashboard')); ?> • <?php echo e(__('app.dashboard.manage_users_teams_esports')); ?></p>
                    </div>
                </div>
                <div class="time-widget">
                    <div class="time-display" id="current-time"><?php echo e(now()->timezone('Asia/Ho_Chi_Minh')->format('H:i:s')); ?></div>
                    <p class="text-[#94a3b8] text-sm mt-1"><?php echo e(now()->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y')); ?></p>
                    <p class="text-[#00E5FF] text-sm font-semibold"><?php echo e(now()->timezone('Asia/Ho_Chi_Minh')->locale(app()->getLocale())->dayName); ?></p>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid mb-6">
            <!-- Total Users -->
            <div class="stat-card">
                <div class="flex items-start justify-between mb-3">
                    <div class="stat-icon cyan"><i class="fas fa-users text-white text-xl"></i></div>
                    <span class="stat-badge up"><i class="fas fa-arrow-up"></i> +12%</span>
                </div>
                <p class="stat-label"><i class="fas fa-users text-xs"></i> <?php echo e(__('app.dashboard.total_users')); ?></p>
                <h2 class="stat-number"><?php echo e(number_format($stats['total_users'])); ?></h2>
                <div class="mt-4 pt-3 border-t border-[rgba(0,229,255,0.1)]">
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="stat-link"><?php echo e(__('app.dashboard.view_details')); ?> <i class="fas fa-arrow-right text-xs"></i></a>
                </div>
            </div>

            <!-- Admins -->
            <div class="stat-card">
                <div class="flex items-start justify-between mb-3">
                    <div class="stat-icon green"><i class="fas fa-user-shield text-white text-xl"></i></div>
                    <span class="stat-badge active"><i class="fas fa-check"></i> Active</span>
                </div>
                <p class="stat-label"><i class="fas fa-user-shield text-xs"></i> Admin</p>
                <h2 class="stat-number"><?php echo e(number_format($stats['total_admins'])); ?></h2>
                <div class="mt-4 pt-3 border-t border-[rgba(0,229,255,0.1)]">
                    <a href="<?php echo e(route('admin.users.index')); ?>?role=admin" class="stat-link"><?php echo e(__('app.dashboard.manage_users')); ?> <i class="fas fa-arrow-right text-xs"></i></a>
                </div>
            </div>

            <!-- Participants -->
            <div class="stat-card">
                <div class="flex items-start justify-between mb-3">
                    <div class="stat-icon blue"><i class="fas fa-users text-white text-xl"></i></div>
                    <span class="stat-badge active"><i class="fas fa-trophy"></i> <?php echo e(__('app.dashboard.competing')); ?></span>
                </div>
                <p class="stat-label"><i class="fas fa-users text-xs"></i> <?php echo e(__('app.dashboard.participants')); ?></p>
                <h2 class="stat-number"><?php echo e(number_format($stats['total_participants'])); ?></h2>
                <div class="mt-4 pt-3 border-t border-[rgba(0,229,255,0.1)]">
                    <a href="<?php echo e(route('admin.users.index')); ?>?role=participant" class="stat-link"><?php echo e(__('app.dashboard.view_details')); ?> <i class="fas fa-arrow-right text-xs"></i></a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="modern-card mb-6">
            <div class="modern-card-header">
                <h3 class="modern-card-title"><i class="fas fa-bolt"></i> Quick Actions</h3>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="quick-action">
                        <div class="quick-action-icon bg-gradient-to-br from-[#00E5FF] to-[#006666]"><i class="fas fa-users text-white"></i></div>
                        <p class="quick-action-text"><?php echo e(__('app.dashboard.manage_users')); ?></p>
                    </a>
                    <a href="<?php echo e(route('admin.teams.index')); ?>" class="quick-action">
                        <div class="quick-action-icon bg-gradient-to-br from-[#10b981] to-[#059669]"><i class="fas fa-users-cog text-white"></i></div>
                        <p class="quick-action-text"><?php echo e(__('app.profile.manage_teams')); ?></p>
                    </a>
                    <a href="<?php echo e(route('admin.tournaments.index')); ?>" class="quick-action">
                        <div class="quick-action-icon bg-gradient-to-br from-[#6366f1] to-[#4f46e5]"><i class="fas fa-trophy text-white"></i></div>
                        <p class="quick-action-text"><?php echo e(__('app.profile.manage_tournaments')); ?></p>
                    </a>
                    <a href="<?php echo e(route('admin.games.index')); ?>" class="quick-action">
                        <div class="quick-action-icon bg-gradient-to-br from-[#f59e0b] to-[#d97706]"><i class="fas fa-gamepad text-white"></i></div>
                        <p class="quick-action-text"><?php echo e(__('app.profile.manage_games')); ?></p>
                    </a>
                    <a href="<?php echo e(route('posts.index')); ?>" class="quick-action">
                        <div class="quick-action-icon bg-gradient-to-br from-[#ec4899] to-[#db2777]"><i class="fas fa-newspaper text-white"></i></div>
                        <p class="quick-action-text"><?php echo e(__('app.nav.posts')); ?></p>
                    </a>
                    <a href="<?php echo e(route('chat.index')); ?>" class="quick-action">
                        <div class="quick-action-icon bg-gradient-to-br from-[#a855f7] to-[#9333ea]"><i class="fas fa-comments text-white"></i></div>
                        <p class="quick-action-text"><?php echo e(__('app.nav.chat')); ?></p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Charts & Role Distribution -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-6">
            <div class="modern-card xl:col-span-2">
                <div class="modern-card-header">
                    <h3 class="modern-card-title"><i class="fas fa-chart-line"></i> User Growth</h3>
                    <button class="btn-ghost" onclick="location.reload()"><i class="fas fa-sync-alt"></i></button>
                </div>
                <div class="p-5"><canvas id="userGrowthChart" height="100"></canvas></div>
            </div>
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="modern-card-title"><i class="fas fa-chart-pie"></i> <?php echo e(__('app.dashboard.activity_statistics')); ?></h3>
                </div>
                <div class="p-5 space-y-4">
                    <?php $__currentLoopData = $stats['user_roles_distribution']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($role->user_role !== 'super_admin'): ?>
                    <div class="role-card">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center <?php echo e($role->user_role === 'admin' ? 'bg-gradient-to-br from-[#004d40] to-[#00796b]' : ($role->user_role === 'participant' ? 'bg-gradient-to-br from-[#1a237e] to-[#3949ab]' : 'bg-gradient-to-br from-[#000055] to-[#006666]')); ?>">
                                <i class="fas <?php echo e($role->user_role === 'admin' ? 'fa-user-shield' : ($role->user_role === 'participant' ? 'fa-gamepad' : 'fa-users')); ?> text-white text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-white font-semibold text-sm"><?php echo e(ucfirst(str_replace('_', ' ', $role->user_role))); ?></span>
                                    <span class="text-[#00E5FF] font-bold"><?php echo e($role->count); ?></span>
                                </div>
                                <div class="progress-track"><div class="progress-fill" style="width: <?php echo e(($role->count / max($stats['total_users'], 1)) * 100); ?>%"></div></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h3 class="modern-card-title"><i class="fas fa-users"></i> <?php echo e(__('app.dashboard.recent_users')); ?></h3>
                <div class="flex gap-3">
                    <button class="btn-ghost" onclick="location.reload()"><i class="fas fa-sync-alt mr-1"></i> <?php echo e(__('app.common.refresh')); ?></button>
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn-neon"><i class="fas fa-arrow-right"></i> <?php echo e(__('app.dashboard.view_all')); ?></a>
                </div>
            </div>
            <div class="p-5">
                <?php echo $__env->make('dashboard.partials.recent-users', ['recent_users' => $stats['recent_users']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function updateTime() {
        const now = new Date();
        document.getElementById('current-time').textContent = now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
    setInterval(updateTime, 1000);

    const ctx = document.getElementById('userGrowthChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{ label: 'Users', data: [5, 8, 12, 18, 25, 32, 38, 45, 52, 60, 70, <?php echo e($stats['total_users']); ?>], borderColor: '#00E5FF', backgroundColor: 'rgba(0, 229, 255, 0.1)', fill: true, tension: 0.4, borderWidth: 3, pointBackgroundColor: '#00E5FF', pointBorderColor: '#000814', pointBorderWidth: 2, pointRadius: 4 }]
            },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { grid: { color: 'rgba(0, 229, 255, 0.1)' }, ticks: { color: '#94a3b8' }, beginAtZero: true }, x: { grid: { color: 'rgba(0, 229, 255, 0.05)' }, ticks: { color: '#94a3b8' } } } }
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\MarketPlace\resources\views/dashboard/super-admin.blade.php ENDPATH**/ ?>