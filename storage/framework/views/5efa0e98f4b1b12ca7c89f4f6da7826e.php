

<?php $__env->startSection('title', 'Quản lý Admin'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .admins-container { background: #000814; min-height: 100vh; }

    /* Hero Section */
    .admins-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .admins-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #00E5FF, transparent);
    }
    .hero-icon {
        width: 60px; height: 60px; min-width: 60px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 0 25px rgba(245, 158, 11, 0.3);
    }
    .hero-icon i { font-size: 1.5rem; color: white; }
    .hero-title { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; color: #00E5FF; margin: 0; }
    .hero-subtitle { color: #94a3b8; font-size: 0.9rem; margin: 0.25rem 0 0 0; }

    .btn-neon {
        background: linear-gradient(135deg, #000055, #000077);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.4);
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-neon:hover {
        background: rgba(0, 229, 255, 0.15);
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.4);
        color: #FFFFFF;
        transform: translateY(-2px);
    }
    .btn-neon-success {
        background: linear-gradient(135deg, #065f46, #047857);
        border-color: rgba(34, 197, 94, 0.4);
        color: #22c55e;
    }
    .btn-neon-success:hover { 
        box-shadow: 0 0 20px rgba(34, 197, 94, 0.4); 
        color: #4ade80;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .stat-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .stat-icon {
        width: 50px; height: 50px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
    }
    .stat-icon.cyan { background: rgba(0, 229, 255, 0.15); color: #00E5FF; }
    .stat-icon.orange { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .stat-icon.purple { background: rgba(139, 92, 246, 0.15); color: #8b5cf6; }
    .stat-icon.green { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
    .stat-value { font-size: 1.75rem; font-weight: 700; color: #FFFFFF; line-height: 1; }
    .stat-label { font-size: 0.8rem; color: #64748b; margin-top: 0.25rem; }

    /* Table Card */
    .table-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .table-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .table-title {
        color: #00E5FF;
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .table-title i { opacity: 0.8; }
    .table-count {
        background: rgba(0, 229, 255, 0.1);
        color: #00E5FF;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .admins-table { width: 100%; border-collapse: collapse; }
    .admins-table th {
        background: rgba(0, 229, 255, 0.05);
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem 1.5rem;
        text-align: left;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }
    .admins-table td {
        padding: 1rem 1.5rem;
        color: #e2e8f0;
        font-size: 0.875rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.05);
        vertical-align: middle;
    }
    .admins-table tbody tr { transition: all 0.3s ease; }
    .admins-table tbody tr:hover { background: rgba(0, 229, 255, 0.05); }
    .admins-table tbody tr:last-child td { border-bottom: none; }

    .admin-avatar { 
        width: 45px; height: 45px; 
        border-radius: 12px; 
        object-fit: cover; 
        border: 2px solid rgba(245, 158, 11, 0.4); 
    }
    .admin-name { font-weight: 600; color: #FFFFFF; }
    .admin-email { color: #64748b; font-size: 0.8rem; }

    /* Permission Badges */
    .permission-badges { display: flex; flex-wrap: wrap; gap: 0.35rem; max-width: 350px; }
    .permission-badge {
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(0, 229, 255, 0.1);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.2);
    }
    .permission-badge i { font-size: 0.6rem; }
    .permission-badge.no-perm {
        background: rgba(100, 116, 139, 0.1);
        color: #64748b;
        border-color: rgba(100, 116, 139, 0.2);
    }

    /* Action Buttons */
    .action-buttons { display: flex; gap: 0.5rem; }
    .btn-action { 
        padding: 0.5rem 0.75rem; 
        border-radius: 8px; 
        font-size: 0.8rem; 
        transition: all 0.3s ease; 
        cursor: pointer; 
        border: 1px solid transparent; 
        background: transparent;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .btn-action-edit { color: #f59e0b; border-color: rgba(245, 158, 11, 0.3); }
    .btn-action-edit:hover { background: rgba(245, 158, 11, 0.15); }
    .btn-action-danger { color: #ef4444; border-color: rgba(239, 68, 68, 0.3); }
    .btn-action-danger:hover { background: rgba(239, 68, 68, 0.15); }

    /* Pending Invitations */
    .invitation-card {
        background: rgba(139, 92, 246, 0.05);
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .invitation-info { display: flex; align-items: center; gap: 1rem; }
    .invitation-icon {
        width: 40px; height: 40px;
        background: rgba(139, 92, 246, 0.15);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: #8b5cf6;
    }
    .invitation-email { color: #FFFFFF; font-weight: 500; }
    .invitation-meta { color: #64748b; font-size: 0.8rem; display: flex; gap: 1rem; flex-wrap: wrap; }
    .invitation-actions { display: flex; gap: 0.5rem; }
    .btn-sm {
        padding: 0.4rem 0.75rem;
        font-size: 0.75rem;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .btn-sm-resend { background: rgba(34, 197, 94, 0.15); color: #22c55e; border-color: rgba(34, 197, 94, 0.3); }
    .btn-sm-resend:hover { background: rgba(34, 197, 94, 0.25); }
    .btn-sm-cancel { background: rgba(239, 68, 68, 0.15); color: #ef4444; border-color: rgba(239, 68, 68, 0.3); }
    .btn-sm-cancel:hover { background: rgba(239, 68, 68, 0.25); }

    /* Empty State */
    .empty-state { 
        text-align: center; 
        padding: 3rem 2rem; 
        color: #64748b;
    }
    .empty-icon { 
        width: 70px; height: 70px; 
        background: rgba(0, 229, 255, 0.1); 
        border-radius: 50%; 
        display: flex; align-items: center; justify-content: center; 
        margin: 0 auto 1rem;
    }
    .empty-icon i { font-size: 1.75rem; color: #64748b; }
    .empty-title { color: #94a3b8; font-size: 1rem; font-weight: 500; margin-bottom: 0.25rem; }
    .empty-text { color: #64748b; font-size: 0.85rem; }

    /* Modal */
    .modal-overlay { 
        position: fixed; top: 0; left: 0; right: 0; bottom: 0; 
        background: rgba(0, 0, 0, 0.8); 
        backdrop-filter: blur(4px); 
        z-index: 99999; 
        display: none; 
        align-items: center; 
        justify-content: center; 
        padding: 1rem; 
    }
    .modal-overlay.active { display: flex; }
    .modal-content-custom { 
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%); 
        border: 1px solid rgba(0, 229, 255, 0.3); 
        border-radius: 20px; 
        width: 100%; 
        max-width: 450px; 
        overflow: hidden; 
    }
    .modal-header-custom { 
        padding: 1.25rem 1.5rem; 
        border-bottom: 1px solid rgba(0, 229, 255, 0.1); 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
    }
    .modal-title-custom { color: #FFFFFF; font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
    .modal-title-custom i { color: #ef4444; }
    .modal-close { background: none; border: none; color: #64748b; cursor: pointer; font-size: 1.25rem; padding: 0.25rem; transition: color 0.2s; }
    .modal-close:hover { color: #FFFFFF; }
    .modal-body-custom { padding: 1.5rem; color: #94a3b8; }
    .modal-footer-custom { 
        padding: 1rem 1.5rem; 
        border-top: 1px solid rgba(0, 229, 255, 0.1); 
        display: flex; 
        gap: 0.75rem; 
        justify-content: flex-end; 
    }
    .btn-modal { padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; border: none; }
    .btn-modal-cancel { background: rgba(100, 116, 139, 0.2); color: #94a3b8; border: 1px solid rgba(100, 116, 139, 0.3); }
    .btn-modal-cancel:hover { background: rgba(100, 116, 139, 0.3); color: #FFFFFF; }
    .btn-modal-danger { background: linear-gradient(135deg, #dc2626, #b91c1c); color: #FFFFFF; }
    .btn-modal-danger:hover { box-shadow: 0 0 20px rgba(239, 68, 68, 0.4); }
    .warning-box { 
        background: rgba(245, 158, 11, 0.1); 
        border: 1px solid rgba(245, 158, 11, 0.3); 
        border-radius: 10px; 
        padding: 0.75rem 1rem; 
        margin-top: 1rem; 
        display: flex; 
        align-items: flex-start; 
        gap: 10px; 
        font-size: 0.85rem; 
        color: #fbbf24; 
    }
    .warning-box i { color: #f59e0b; margin-top: 2px; }

    /* Permissions Reference */
    .permissions-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        padding: 1.5rem;
    }
    .permissions-title {
        color: #00E5FF;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .permissions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
    }
    .perm-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.6rem 0.75rem;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        border: 1px solid rgba(0, 229, 255, 0.1);
    }
    .perm-icon {
        width: 32px; height: 32px;
        background: rgba(0, 229, 255, 0.1);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: #00E5FF;
        font-size: 0.85rem;
    }
    .perm-label { color: #FFFFFF; font-size: 0.8rem; font-weight: 500; }
    .perm-desc { color: #64748b; font-size: 0.7rem; }

    /* Alert messages */
    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    @media (max-width: 768px) {
        .admins-hero { padding: 1.25rem; }
        .hero-content { flex-direction: column; align-items: flex-start !important; gap: 1rem; }
        .btn-neon { width: 100%; justify-content: center; }
        .admins-table { display: block; overflow-x: auto; }
        .permission-badges { max-width: 200px; }
        .invitation-card { flex-direction: column; align-items: flex-start; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="admins-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Hero Section -->
        <div class="admins-hero">
            <div class="hero-content flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="hero-icon"><i class="fas fa-user-shield"></i></div>
                    <div>
                        <h1 class="hero-title">Quản lý Admin</h1>
                        <p class="hero-subtitle">Quản lý danh sách Admin và phân quyền</p>
                    </div>
                </div>
                <div class="hero-buttons flex gap-3">
                    <a href="<?php echo e(route('admin.admins.create')); ?>" class="btn-neon btn-neon-success">
                        <i class="fas fa-user-plus"></i><span>Mời Admin mới</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if(session('success')): ?>
            <div class="alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon cyan"><i class="fas fa-users-cog"></i></div>
                <div>
                    <div class="stat-value"><?php echo e($admins->count()); ?></div>
                    <div class="stat-label">Tổng số Admin</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-crown"></i></div>
                <div>
                    <div class="stat-value"><?php echo e($superAdminCount ?? 1); ?></div>
                    <div class="stat-label">Super Admin</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-envelope-open-text"></i></div>
                <div>
                    <div class="stat-value"><?php echo e($pendingInvitations->count()); ?></div>
                    <div class="stat-label">Lời mời đang chờ</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-key"></i></div>
                <div>
                    <div class="stat-value"><?php echo e(count(\App\Models\AdminPermission::AVAILABLE_PERMISSIONS)); ?></div>
                    <div class="stat-label">Loại quyền hạn</div>
                </div>
            </div>
        </div>

        <!-- Admin List -->
        <div class="table-card">
            <div class="table-header">
                <div class="table-title">
                    <i class="fas fa-user-tie"></i>
                    Danh sách Admin
                </div>
                <span class="table-count"><?php echo e($admins->count()); ?> admin</span>
            </div>

            <?php if($admins->count() > 0): ?>
                <table class="admins-table">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Quyền hạn</th>
                            <th>Ngày cấp quyền</th>
                            <th class="text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img src="<?php echo e(get_avatar_url($admin->avatar)); ?>" 
                                             class="admin-avatar" alt="Avatar">
                                        <div>
                                            <div class="admin-name"><?php echo e($admin->name); ?></div>
                                            <div class="admin-email"><?php echo e($admin->email); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="permission-badges">
                                        <?php
                                            $permissions = $admin->adminPermission?->permissions ?? [];
                                        ?>
                                        <?php if($admin->user_role === 'super_admin'): ?>
                                            <span class="permission-badge" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; border-color: rgba(239, 68, 68, 0.3);">
                                                <i class="fas fa-crown"></i> ALL
                                            </span>
                                        <?php elseif(count($permissions) > 0): ?>
                                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(isset($availablePermissions[$perm])): ?>
                                                    <span class="permission-badge" title="<?php echo e($availablePermissions[$perm]['description']); ?>">
                                                        <i class="<?php echo e($availablePermissions[$perm]['icon']); ?>"></i>
                                                        <?php echo e(Str::limit($availablePermissions[$perm]['label'], 15)); ?>

                                                    </span>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <span class="permission-badge no-perm">
                                                <i class="fas fa-ban"></i> Chưa phân quyền
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if($admin->adminPermission): ?>
                                        <span style="color: #94a3b8;"><?php echo e($admin->adminPermission->created_at->format('d/m/Y')); ?></span>
                                    <?php else: ?>
                                        <span style="color: #64748b;">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($admin->user_role !== 'super_admin'): ?>
                                        <div class="action-buttons justify-end">
                                            <a href="<?php echo e(route('admin.admins.edit-permissions', $admin)); ?>" 
                                               class="btn-action btn-action-edit" title="Chỉnh sửa quyền">
                                                <i class="fas fa-key"></i>
                                            </a>
                                            <button type="button" class="btn-action btn-action-danger" 
                                                    onclick="openRevokeModal(<?php echo e($admin->id); ?>, '<?php echo e($admin->name); ?>')"
                                                    title="Thu hồi quyền Admin">
                                                <i class="fas fa-user-minus"></i>
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #64748b; font-size: 0.8rem;"><i class="fas fa-lock"></i></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-user-shield"></i></div>
                    <div class="empty-title">Chưa có Admin nào</div>
                    <div class="empty-text">Hãy mời Admin đầu tiên để bắt đầu</div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pending Invitations -->
        <div class="table-card">
            <div class="table-header">
                <div class="table-title">
                    <i class="fas fa-envelope-open-text"></i>
                    Lời mời đang chờ
                </div>
                <span class="table-count"><?php echo e($pendingInvitations->count()); ?> lời mời</span>
            </div>

            <div style="padding: 1rem 1.5rem;">
                <?php if($pendingInvitations->count() > 0): ?>
                    <?php $__currentLoopData = $pendingInvitations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invitation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="invitation-card">
                            <div class="invitation-info">
                                <div class="invitation-icon">
                                    <i class="fas fa-paper-plane"></i>
                                </div>
                                <div>
                                    <div class="invitation-email"><?php echo e($invitation->email); ?></div>
                                    <div class="invitation-meta">
                                        <span><i class="fas fa-clock"></i> <?php echo e($invitation->created_at->diffForHumans()); ?></span>
                                        <span><i class="fas fa-hourglass-half"></i> Hết hạn: <?php echo e($invitation->expires_at->format('d/m/Y H:i')); ?></span>
                                        <?php if($invitation->isExpired()): ?>
                                            <span style="color: #ef4444;"><i class="fas fa-exclamation-triangle"></i> Đã hết hạn</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="invitation-actions">
                                <?php if(!$invitation->isExpired()): ?>
                                    <form action="<?php echo e(route('admin.admins.resend-invitation', $invitation)); ?>" method="POST" style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn-sm btn-sm-resend">
                                            <i class="fas fa-redo"></i> Gửi lại
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <form action="<?php echo e(route('admin.admins.cancel-invitation', $invitation)); ?>" method="POST" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-sm btn-sm-cancel" onclick="return confirm('Bạn có chắc muốn hủy lời mời này?')">
                                        <i class="fas fa-times"></i> Hủy
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="empty-state" style="padding: 2rem;">
                        <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                        <div class="empty-title">Không có lời mời nào đang chờ</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Permissions Reference -->
        <div class="permissions-card">
            <div class="permissions-title">
                <i class="fas fa-info-circle"></i>
                Danh sách quyền hạn có sẵn
            </div>
            <div class="permissions-grid">
                <?php $__currentLoopData = $availablePermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="perm-item">
                        <div class="perm-icon">
                            <i class="<?php echo e($permission['icon']); ?>"></i>
                        </div>
                        <div>
                            <div class="perm-label"><?php echo e($permission['label']); ?></div>
                            <div class="perm-desc"><?php echo e(Str::limit($permission['description'], 40)); ?></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Revoke Modal -->
<div class="modal-overlay" id="revokeModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h5 class="modal-title-custom">
                <i class="fas fa-exclamation-triangle"></i>
                Thu hồi quyền Admin
            </h5>
            <button type="button" class="modal-close" onclick="closeRevokeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body-custom">
            <p>Bạn có chắc muốn thu hồi quyền Admin của <strong id="revokeAdminName" style="color: #00E5FF;"></strong>?</p>
            <div class="warning-box">
                <i class="fas fa-exclamation-circle"></i>
                <span>Người dùng này sẽ trở về vai trò Participant và mất tất cả quyền quản trị.</span>
            </div>
        </div>
        <div class="modal-footer-custom">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeRevokeModal()">Hủy</button>
            <form id="revokeForm" method="POST" style="display: inline;">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn-modal btn-modal-danger">
                    <i class="fas fa-user-minus"></i> Thu hồi
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function openRevokeModal(adminId, adminName) {
    document.getElementById('revokeAdminName').textContent = adminName;
    document.getElementById('revokeForm').action = '/admin/admins/' + adminId + '/revoke';
    document.getElementById('revokeModal').classList.add('active');
}

function closeRevokeModal() {
    document.getElementById('revokeModal').classList.remove('active');
}

// Close modal on outside click
document.getElementById('revokeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRevokeModal();
    }
});

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRevokeModal();
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\MarketPlace\resources\views/admin/admins/index.blade.php ENDPATH**/ ?>