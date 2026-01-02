<?php $__env->startSection('title', __('app.profile.manage_users')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .users-container { background: #000814; min-height: 100vh; }

    /* Hero Section */
    .users-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .users-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #00E5FF, transparent);
    }
    .hero-icon {
        width: 60px; height: 60px; min-width: 60px;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 0 25px rgba(34, 197, 94, 0.3);
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
    .btn-neon-success:hover { box-shadow: 0 0 20px rgba(34, 197, 94, 0.4); }

    /* Filter Card */
    .filter-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .filter-label { color: #94a3b8; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem; display: block; }
    .filter-input, .filter-select {
        width: 100%;
        padding: 0.6rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 8px;
        color: #FFFFFF;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }
    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: #00E5FF;
        box-shadow: 0 0 10px rgba(0, 229, 255, 0.2);
    }
    .filter-input::placeholder { color: #64748b; }
    .filter-select option { background: #0d1b2a; color: #FFFFFF; }
    .input-icon-wrapper { position: relative; }
    .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 0.85rem; }
    .input-icon-wrapper .filter-input { padding-left: 2.5rem; }

    /* Table Card */
    .table-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
    }
    .users-table { width: 100%; border-collapse: collapse; }
    .users-table th {
        background: rgba(0, 229, 255, 0.05);
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }
    .users-table td {
        padding: 1rem;
        color: #e2e8f0;
        font-size: 0.875rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.05);
        vertical-align: middle;
    }
    .users-table tbody tr { transition: all 0.3s ease; }
    .users-table tbody tr:hover { background: rgba(0, 229, 255, 0.05); }

    .user-avatar { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(0, 229, 255, 0.3); }
    .user-avatar-fallback {
        width: 45px; height: 45px; border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 700; font-size: 1rem;
    }
    .user-name { font-weight: 600; color: #FFFFFF; }
    .user-id { color: #64748b; font-size: 0.75rem; }
    .user-email { color: #94a3b8; }

    /* Role Badges */
    .badge-role { padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
    .role-super_admin { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    .role-admin { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .role-participant { background: rgba(99, 102, 241, 0.2); color: #818cf8; }

    /* Status Badges */
    .badge-status { padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; cursor: pointer; }
    .status-active { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .status-suspended { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .status-banned { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    .status-deleted { background: rgba(100, 116, 139, 0.2); color: #94a3b8; }

    /* Action Buttons */
    .btn-action { padding: 0.4rem 0.6rem; border-radius: 8px; font-size: 0.8rem; transition: all 0.3s ease; cursor: pointer; border: 1px solid transparent; background: transparent; }
    .btn-action-view { color: #00E5FF; border-color: rgba(0, 229, 255, 0.3); }
    .btn-action-view:hover { background: rgba(0, 229, 255, 0.15); }
    .btn-action-edit { color: #f59e0b; border-color: rgba(245, 158, 11, 0.3); }
    .btn-action-edit:hover { background: rgba(245, 158, 11, 0.15); }
    .btn-action-settings { color: #94a3b8; border-color: rgba(148, 163, 184, 0.3); }
    .btn-action-settings:hover { background: rgba(148, 163, 184, 0.15); color: #FFFFFF; }
    .btn-action-disabled { color: #475569; border-color: rgba(71, 85, 105, 0.3); cursor: not-allowed; }

    /* Dropdown */
    .dropdown-wrapper { position: relative; display: inline-block; }
    .dropdown-menu-custom {
        position: absolute; top: 100%; right: 0;
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px; min-width: 160px; padding: 0.5rem 0;
        z-index: 1000; display: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }
    .dropdown-wrapper:hover .dropdown-menu-custom { display: block; }
    .dropdown-item-custom {
        display: flex; align-items: center; gap: 0.5rem;
        padding: 0.5rem 1rem; color: #94a3b8; font-size: 0.85rem;
        text-decoration: none; transition: all 0.2s ease;
        cursor: pointer; border: none; background: none; width: 100%; text-align: left;
    }
    .dropdown-item-custom:hover { background: rgba(0, 229, 255, 0.1); color: #FFFFFF; }
    .dropdown-item-custom.text-success { color: #22c55e; }
    .dropdown-item-custom.text-warning { color: #f59e0b; }
    .dropdown-item-custom.text-danger { color: #ef4444; }
    .dropdown-divider { border-top: 1px solid rgba(0, 229, 255, 0.1); margin: 0.5rem 0; }

    /* Empty State */
    .empty-state { text-align: center; padding: 4rem 2rem; }
    .empty-icon { width: 80px; height: 80px; background: rgba(0, 229, 255, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
    .empty-icon i { font-size: 2rem; color: #64748b; }
    .empty-title { color: #FFFFFF; font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; }
    .empty-text { color: #64748b; font-size: 0.9rem; }

    .custom-checkbox { width: 18px; height: 18px; accent-color: #00E5FF; cursor: pointer; }
    .pagination-wrapper { padding: 1rem 1.5rem; border-top: 1px solid rgba(0, 229, 255, 0.1); display: flex; justify-content: space-between; align-items: center; }
    .pagination-info { color: #64748b; font-size: 0.85rem; }

    /* Modal */
    .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(4px); z-index: 99999; display: none; align-items: center; justify-content: center; padding: 1rem; }
    .modal-overlay.active { display: flex; }
    .modal-content-custom { background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 20px; width: 100%; max-width: 450px; overflow: hidden; }
    .modal-header-custom { padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0, 229, 255, 0.1); display: flex; align-items: center; justify-content: space-between; }
    .modal-title-custom { color: #FFFFFF; font-size: 1.1rem; font-weight: 600; }
    .modal-body-custom { padding: 1.5rem; color: #94a3b8; }
    .modal-footer-custom { padding: 1rem 1.5rem; border-top: 1px solid rgba(0, 229, 255, 0.1); display: flex; gap: 0.75rem; justify-content: flex-end; }
    .btn-modal { padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; }
    .btn-modal-cancel { background: rgba(100, 116, 139, 0.2); color: #94a3b8; border: 1px solid rgba(100, 116, 139, 0.3); }
    .btn-modal-cancel:hover { background: rgba(100, 116, 139, 0.3); color: #FFFFFF; }
    .btn-modal-primary { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #FFFFFF; border: none; }
    .btn-modal-primary:hover { box-shadow: 0 0 20px rgba(99, 102, 241, 0.4); }
    .btn-modal-danger { background: linear-gradient(135deg, #dc2626, #b91c1c); color: #FFFFFF; border: none; }
    .btn-modal-danger:hover { box-shadow: 0 0 20px rgba(239, 68, 68, 0.4); }
    .warning-box { background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 10px; padding: 0.75rem 1rem; margin-top: 1rem; display: flex; align-items: flex-start; gap: 10px; font-size: 0.85rem; color: #fbbf24; }
    .warning-box i { color: #f59e0b; margin-top: 2px; }

    /* Offcanvas */
    .offcanvas-custom { position: fixed; top: 0; right: -400px; width: 400px; height: 100%; background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%); border-left: 1px solid rgba(0, 229, 255, 0.2); z-index: 100000; transition: right 0.3s ease; overflow-y: auto; }
    .offcanvas-custom.active { right: 0; }
    .offcanvas-header-custom { padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0, 229, 255, 0.1); display: flex; align-items: center; justify-content: space-between; }
    .offcanvas-title-custom { color: #FFFFFF; font-size: 1.1rem; font-weight: 600; }
    .offcanvas-body-custom { padding: 1.5rem; color: #94a3b8; }
    .offcanvas-backdrop { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 99999; display: none; }
    .offcanvas-backdrop.active { display: block; }

    @media (max-width: 768px) {
        .users-hero { padding: 1.25rem; }
        .hero-content { flex-direction: column; align-items: flex-start !important; gap: 1rem; }
        .hero-buttons { width: 100%; flex-direction: column; }
        .btn-neon { width: 100%; justify-content: center; }
        .filter-grid { grid-template-columns: 1fr !important; }
        .users-table { display: block; overflow-x: auto; }
        .offcanvas-custom { width: 100%; right: -100%; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="users-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Hero Section -->
        <div class="users-hero">
            <div class="hero-content flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="hero-icon"><i class="fas fa-users"></i></div>
                    <div>
                        <h1 class="hero-title"><?php echo e(__('app.profile.manage_users')); ?></h1>
                        <p class="hero-subtitle"><?php echo e(__('app.profile.manage_users_description')); ?></p>
                    </div>
                </div>
                <div class="hero-buttons flex gap-3">
                    <button type="button" class="btn-neon btn-neon-success" onclick="exportUsers()">
                        <i class="fas fa-file-excel"></i><span><?php echo e(__('app.users.export_csv')); ?></span>
                    </button>
                    <button type="button" class="btn-neon" id="bulkActionBtn" disabled onclick="openBulkModal()">
                        <i class="fas fa-layer-group"></i><span><?php echo e(__('app.users.bulk_operations')); ?></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="filter-card">
            <form method="GET" action="<?php echo e(route('admin.users.index')); ?>" id="filterForm">
                <div class="filter-grid grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="filter-label"><?php echo e(__('app.search.search')); ?></label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-search input-icon"></i>
                            <input type="text" class="filter-input" name="search" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('app.users.search_placeholder')); ?>">
                        </div>
                    </div>
                    <div>
                        <label class="filter-label"><?php echo e(__('app.users.role')); ?></label>
                        <select class="filter-select" name="role">
                            <option value=""><?php echo e(__('app.users.all')); ?></option>
                            <?php if(Auth::user()->user_role === 'super_admin'): ?>
                            <option value="super_admin" <?php echo e(request('role') === 'super_admin' ? 'selected' : ''); ?>>Super Admin</option>
                            <?php endif; ?>
                            <option value="admin" <?php echo e(request('role') === 'admin' ? 'selected' : ''); ?>>Admin</option>
                            <option value="participant" <?php echo e(request('role') === 'participant' ? 'selected' : ''); ?>>Participant</option>
                        </select>
                    </div>
                    <div>
                        <label class="filter-label"><?php echo e(__('app.users.status')); ?></label>
                        <select class="filter-select" name="status">
                            <option value=""><?php echo e(__('app.users.all')); ?></option>
                            <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>><?php echo e(__('app.users.active')); ?></option>
                            <option value="suspended" <?php echo e(request('status') === 'suspended' ? 'selected' : ''); ?>><?php echo e(__('app.users.suspended')); ?></option>
                            <option value="banned" <?php echo e(request('status') === 'banned' ? 'selected' : ''); ?>><?php echo e(__('app.users.banned')); ?></option>
                            <option value="deleted" <?php echo e(request('status') === 'deleted' ? 'selected' : ''); ?>><?php echo e(__('app.users.deleted')); ?></option>
                        </select>
                    </div>
                    <div>
                        <label class="filter-label"><?php echo e(__('app.users.sort_by')); ?></label>
                        <select class="filter-select" name="sort">
                            <option value="created_at" <?php echo e(request('sort') === 'created_at' ? 'selected' : ''); ?>><?php echo e(__('app.users.creation_date')); ?></option>
                            <option value="last_login" <?php echo e(request('sort') === 'last_login' ? 'selected' : ''); ?>><?php echo e(__('app.users.last_login')); ?></option>
                            <option value="name" <?php echo e(request('sort') === 'name' ? 'selected' : ''); ?>><?php echo e(__('app.users.name_az')); ?></option>
                            <option value="email" <?php echo e(request('sort') === 'email' ? 'selected' : ''); ?>><?php echo e(__('app.users.email_az')); ?></option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn-neon flex-1"><i class="fas fa-filter"></i><span><?php echo e(__('app.search.filter')); ?></span></button>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn-neon"><i class="fas fa-times"></i></a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="table-card">
            <div class="overflow-x-auto">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th class="w-12"><input type="checkbox" id="selectAll" class="custom-checkbox"></th>
                            <th class="w-16">Avatar</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th><?php echo e(__('app.users.role')); ?></th>
                            <th><?php echo e(__('app.users.status')); ?></th>
                            <th><?php echo e(__('app.users.registration_date')); ?></th>
                            <th><?php echo e(__('app.users.last_login')); ?></th>
                            <th class="w-32"><?php echo e(__('app.common.actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><input type="checkbox" class="custom-checkbox user-checkbox" value="<?php echo e($user->id); ?>" data-role="<?php echo e($user->user_role); ?>"></td>
                            <td>
                                <?php if($user->avatar): ?>
                                <img src="<?php echo e(get_avatar_url($user->avatar)); ?>" class="user-avatar" alt="Avatar">
                                <?php else: ?>
                                <div class="user-avatar-fallback"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="user-name"><?php echo e($user->name); ?></div>
                                <div class="user-id">ID: <?php echo e($user->id); ?></div>
                            </td>
                            <td><span class="user-email"><?php echo e($user->email); ?></span></td>
                            <td>
                                <span class="badge-role role-<?php echo e($user->user_role); ?>">
                                    <?php if($user->user_role === 'super_admin'): ?> Super Admin
                                    <?php elseif($user->user_role === 'admin'): ?> Admin
                                    <?php elseif($user->user_role === 'participant'): ?> Participant
                                    <?php else: ?> User <?php endif; ?>
                                </span>
                            </td>
                            <td>
                                <div class="dropdown-wrapper">
                                    <span class="badge-status status-<?php echo e($user->status); ?>" id="status-badge-<?php echo e($user->id); ?>">
                                        <?php if($user->status === 'active'): ?> <?php echo e(__('app.users.active')); ?>

                                        <?php elseif($user->status === 'suspended'): ?> <?php echo e(__('app.users.suspended')); ?>

                                        <?php elseif($user->status === 'banned'): ?> <?php echo e(__('app.users.banned')); ?>

                                        <?php else: ?> <?php echo e(__('app.users.deleted')); ?> <?php endif; ?>
                                    </span>
                                    <div class="dropdown-menu-custom">
                                        <?php if($user->status !== 'active'): ?><button class="dropdown-item-custom text-success" onclick="changeStatus(<?php echo e($user->id); ?>, 'active')"><i class="fas fa-check"></i> <?php echo e(__('app.users.activate')); ?></button><?php endif; ?>
                                        <?php if($user->status !== 'suspended'): ?><button class="dropdown-item-custom text-warning" onclick="changeStatus(<?php echo e($user->id); ?>, 'suspended')"><i class="fas fa-pause"></i> <?php echo e(__('app.users.suspend')); ?></button><?php endif; ?>
                                        <?php if($user->status !== 'banned'): ?><button class="dropdown-item-custom text-danger" onclick="changeStatus(<?php echo e($user->id); ?>, 'banned')"><i class="fas fa-ban"></i> <?php echo e(__('app.users.ban')); ?></button><?php endif; ?>
                                        <?php if($user->status !== 'deleted'): ?><div class="dropdown-divider"></div><button class="dropdown-item-custom text-danger" onclick="changeStatus(<?php echo e($user->id); ?>, 'deleted')"><i class="fas fa-trash"></i> <?php echo e(__('app.common.delete')); ?></button><?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($user->created_at->format('d/m/Y')); ?></td>
                            <td><?php echo e($user->last_login ? $user->last_login->format('d/m/Y H:i') : __('app.users.never_logged_in')); ?></td>
                            <td>
                                <div class="flex gap-2">
                                    <button type="button" class="btn-action btn-action-view" onclick="viewUser(<?php echo e($user->id); ?>)" title="<?php echo e(__('app.dashboard.view_details')); ?>"><i class="fas fa-eye"></i></button>
                                    <?php
                                    $currentUser = Auth::user();
                                    $canEditUser = false;
                                    if ($currentUser->user_role === 'super_admin') {
                                        $canEditUser = $user->id !== $currentUser->id;
                                    } elseif ($currentUser->user_role === 'admin') {
                                        $canEditUser = $user->id !== $currentUser->id && !in_array($user->user_role, ['super_admin', 'admin']);
                                    }
                                    ?>
                                    <?php if($canEditUser): ?>
                                    <button type="button" class="btn-action btn-action-edit" onclick="editUser(<?php echo e($user->id); ?>)" title="<?php echo e(__('app.common.edit')); ?>"><i class="fas fa-edit"></i></button>
                                    <div class="dropdown-wrapper">
                                        <button type="button" class="btn-action btn-action-settings" title="<?php echo e(__('app.users.change_status')); ?>"><i class="fas fa-cog"></i></button>
                                        <div class="dropdown-menu-custom">
                                            <?php if($user->status !== 'active'): ?><button class="dropdown-item-custom text-success" onclick="changeStatus(<?php echo e($user->id); ?>, 'active')"><i class="fas fa-check"></i> <?php echo e(__('app.users.activate')); ?></button><?php endif; ?>
                                            <?php if($user->status !== 'suspended'): ?><button class="dropdown-item-custom text-warning" onclick="changeStatus(<?php echo e($user->id); ?>, 'suspended')"><i class="fas fa-pause"></i> <?php echo e(__('app.users.suspend')); ?></button><?php endif; ?>
                                            <?php if($user->status !== 'banned'): ?><button class="dropdown-item-custom text-danger" onclick="changeStatus(<?php echo e($user->id); ?>, 'banned')"><i class="fas fa-ban"></i> <?php echo e(__('app.users.ban')); ?></button><?php endif; ?>
                                            <?php if($user->status !== 'deleted'): ?><div class="dropdown-divider"></div><button class="dropdown-item-custom text-danger" onclick="changeStatus(<?php echo e($user->id); ?>, 'deleted')"><i class="fas fa-trash"></i> <?php echo e(__('app.common.delete')); ?></button><?php endif; ?>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <button type="button" class="btn-action btn-action-disabled" disabled title="<?php echo e(__('app.users.no_permission_to_edit')); ?>"><i class="fas fa-lock"></i></button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fas fa-users"></i></div>
                                    <h3 class="empty-title"><?php echo e(__('app.users.no_users_found')); ?></h3>
                                    <p class="empty-text">Không tìm thấy người dùng nào phù hợp với bộ lọc</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($users->hasPages()): ?>
            <div class="pagination-wrapper">
                <div class="pagination-info"><?php echo e(__('app.users.showing')); ?> <?php echo e($users->firstItem()); ?> - <?php echo e($users->lastItem()); ?> <?php echo e(__('app.users.in_total')); ?> <?php echo e($users->total()); ?> <?php echo e(__('app.users.users')); ?></div>
                <?php echo e($users->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal-overlay" id="bulkActionModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h5 class="modal-title-custom"><i class="fas fa-layer-group mr-2"></i><?php echo e(__('app.users.bulk_operations')); ?></h5>
            <button type="button" class="btn-action" onclick="closeBulkModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body-custom">
            <p><?php echo e(__('app.users.selected_count')); ?> <span id="selectedCount" style="color: #00E5FF; font-weight: bold;">0</span> <?php echo e(__('app.users.users')); ?></p>
            <div style="margin-top: 1rem;">
                <label class="filter-label"><?php echo e(__('app.users.select_action')); ?>:</label>
                <select class="filter-select" id="bulkAction">
                    <option value=""><?php echo e(__('app.users.select_action')); ?></option>
                    <option value="activate"><?php echo e(__('app.users.activate')); ?></option>
                    <option value="suspend"><?php echo e(__('app.users.suspend')); ?></option>
                    <option value="ban"><?php echo e(__('app.users.ban')); ?></option>
                    <option value="delete"><?php echo e(__('app.common.delete')); ?></option>
                </select>
            </div>
            <div class="warning-box"><i class="fas fa-exclamation-triangle"></i><span><?php echo e(__('app.users.bulk_action_warning')); ?></span></div>
        </div>
        <div class="modal-footer-custom">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeBulkModal()"><?php echo e(__('app.common.cancel')); ?></button>
            <button type="button" class="btn-modal btn-modal-primary" onclick="executeBulkAction()"><?php echo e(__('app.users.execute')); ?></button>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal-content-custom" style="max-width: 380px;">
        <div class="modal-header-custom">
            <h5 class="modal-title-custom" id="confirmModalTitle"><?php echo e(__('app.users.confirm_action')); ?></h5>
            <button type="button" class="btn-action" onclick="closeConfirmModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body-custom" id="confirmModalBody"><?php echo e(__('app.users.confirm_action_message')); ?></div>
        <div class="modal-footer-custom">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeConfirmModal()"><?php echo e(__('app.common.cancel')); ?></button>
            <button type="button" class="btn-modal btn-modal-danger" id="confirmActionBtn"><?php echo e(__('app.common.confirm')); ?></button>
        </div>
    </div>
</div>

<!-- User Details Offcanvas -->
<div class="offcanvas-backdrop" id="offcanvasBackdrop" onclick="closeOffcanvas()"></div>
<div class="offcanvas-custom" id="userDetailsOffcanvas">
    <div class="offcanvas-header-custom">
        <h5 class="offcanvas-title-custom"><?php echo e(__('app.users.user_info')); ?></h5>
        <button type="button" class="btn-action" onclick="closeOffcanvas()"><i class="fas fa-times"></i></button>
    </div>
    <div class="offcanvas-body-custom" id="userDetailsContent">
        <div style="text-align: center; padding: 2rem; color: #64748b;"><?php echo e(__('app.users.select_user_to_view_details')); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = this.checked);
        updateBulkActionButton();
    });
    document.querySelectorAll('.user-checkbox').forEach(cb => cb.addEventListener('change', updateBulkActionButton));
    document.querySelectorAll('.filter-select').forEach(select => {
        select.addEventListener('change', function() { document.getElementById('filterForm').submit(); });
    });
});

function updateBulkActionButton() {
    const checked = document.querySelectorAll('.user-checkbox:checked').length;
    document.getElementById('bulkActionBtn').disabled = checked === 0;
    document.getElementById('selectedCount').textContent = checked;
}

function viewUser(id) {
    document.getElementById('offcanvasBackdrop').classList.add('active');
    document.getElementById('userDetailsOffcanvas').classList.add('active');
    document.getElementById('userDetailsContent').innerHTML = '<div style="text-align: center; padding: 2rem;"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #00E5FF;"></i><p style="margin-top: 1rem; color: #94a3b8;">Đang tải...</p></div>';
    
    fetch(`<?php echo e(url('admin/users')); ?>/${id}`)
        .then(r => r.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const content = doc.querySelector('#user-detail-root') || doc.querySelector('.container-fluid');
            document.getElementById('userDetailsContent').innerHTML = content ? content.innerHTML : html;
        })
        .catch(() => {
            document.getElementById('userDetailsContent').innerHTML = '<div style="text-align: center; padding: 2rem; color: #ef4444;">Không thể tải thông tin người dùng</div>';
        });
}

function closeOffcanvas() {
    document.getElementById('offcanvasBackdrop').classList.remove('active');
    document.getElementById('userDetailsOffcanvas').classList.remove('active');
}

function editUser(id) {
    window.location.href = `<?php echo e(url('admin/users')); ?>/${id}/edit`;
}

let pendingStatusChange = null;
function changeStatus(userId, status) {
    pendingStatusChange = { userId, status };
    const statusText = {
        'active': '<?php echo e(__("app.users.activate")); ?>',
        'suspended': '<?php echo e(__("app.users.suspend")); ?>',
        'banned': '<?php echo e(__("app.users.ban")); ?>',
        'deleted': '<?php echo e(__("app.common.delete")); ?>'
    };
    document.getElementById('confirmModalBody').innerHTML = `Bạn có chắc chắn muốn <strong style="color: #00E5FF;">${statusText[status]}</strong> người dùng này?`;
    document.getElementById('confirmModal').classList.add('active');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.remove('active');
    pendingStatusChange = null;
}

document.getElementById('confirmActionBtn').addEventListener('click', function() {
    if (!pendingStatusChange) return;
    const { userId, status } = pendingStatusChange;
    
    fetch(`<?php echo e(url('admin/users')); ?>/${userId}/status`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
        body: JSON.stringify({ status: status })
    }).then(r => r.json()).then(data => {
        if (data.success) location.reload();
        else alert(data.message || 'Error');
    }).catch(() => alert('Error'));
    
    closeConfirmModal();
});

function openBulkModal() { document.getElementById('bulkActionModal').classList.add('active'); }
function closeBulkModal() { document.getElementById('bulkActionModal').classList.remove('active'); }

function executeBulkAction() {
    const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
    const action = document.getElementById('bulkAction').value;
    if (!action) { alert('<?php echo e(__("app.users.please_select_action")); ?>'); return; }
    
    fetch('<?php echo e(route("admin.users.bulk-update")); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
        body: JSON.stringify({ user_ids: selectedIds, action: action })
    }).then(r => r.json()).then(data => {
        if (data.success) location.reload();
        else alert(data.message);
    }).catch(() => alert('Error'));
    closeBulkModal();
}

function exportUsers() {
    window.open(`<?php echo e(route('admin.users.export')); ?>?${new URLSearchParams(window.location.search).toString()}`);
}

// Close modals on overlay click
document.getElementById('bulkActionModal').addEventListener('click', function(e) { if (e.target === this) closeBulkModal(); });
document.getElementById('confirmModal').addEventListener('click', function(e) { if (e.target === this) closeConfirmModal(); });
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') { closeBulkModal(); closeConfirmModal(); closeOffcanvas(); } });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\MarketPlace\resources\views/admin/users/index.blade.php ENDPATH**/ ?>