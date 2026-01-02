

<?php $__env->startSection('title', 'Chỉnh sửa quyền Admin'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .edit-container { background: #000814; min-height: 100vh; }

    /* Hero Section */
    .edit-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .edit-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #f59e0b, transparent);
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

    .btn-back {
        width: 45px; height: 45px;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 12px;
        color: #94a3b8;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-back:hover { 
        background: rgba(0, 229, 255, 0.1);
        border-color: rgba(0, 229, 255, 0.4);
        color: #00E5FF; 
    }

    /* User Info Card */
    .user-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(245, 158, 11, 0.2);
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .user-avatar {
        width: 60px; height: 60px;
        border-radius: 14px;
        object-fit: cover;
        border: 2px solid rgba(245, 158, 11, 0.4);
    }
    .user-name { color: #FFFFFF; font-size: 1.1rem; font-weight: 600; margin-bottom: 0.25rem; }
    .user-email { color: #64748b; font-size: 0.9rem; }
    .user-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.75rem;
        background: rgba(245, 158, 11, 0.15);
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-radius: 20px;
        color: #f59e0b;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 0.5rem;
    }

    /* Form Cards */
    .form-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .form-card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .form-card-title {
        color: #00E5FF;
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }
    .form-card-body { padding: 1.5rem; }

    /* Permission Cards */
    .permissions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }
    .permission-card {
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(0, 229, 255, 0.1);
        border-radius: 12px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }
    .permission-card:hover {
        background: rgba(0, 229, 255, 0.05);
        border-color: rgba(0, 229, 255, 0.3);
    }
    .permission-card.selected {
        background: rgba(0, 229, 255, 0.1);
        border-color: #00E5FF;
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.15);
    }
    .permission-checkbox { margin-top: 2px; }
    .permission-checkbox input {
        width: 18px; height: 18px;
        accent-color: #00E5FF;
        cursor: pointer;
    }
    .permission-icon {
        width: 40px; height: 40px; min-width: 40px;
        background: rgba(0, 229, 255, 0.1);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: #00E5FF;
        font-size: 1rem;
    }
    .permission-card.selected .permission-icon { background: rgba(0, 229, 255, 0.2); }
    .permission-label {
        color: #FFFFFF;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
        cursor: pointer;
    }
    .permission-desc {
        color: #64748b;
        font-size: 0.8rem;
        line-height: 1.4;
    }

    /* Quick Actions */
    .quick-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 1rem;
    }
    .btn-quick {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-quick-all {
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.3);
        color: #00E5FF;
    }
    .btn-quick-all:hover { background: rgba(0, 229, 255, 0.2); }
    .btn-quick-none {
        background: rgba(100, 116, 139, 0.1);
        border: 1px solid rgba(100, 116, 139, 0.3);
        color: #94a3b8;
    }
    .btn-quick-none:hover { background: rgba(100, 116, 139, 0.2); }

    /* Buttons */
    .btn-neon {
        background: linear-gradient(135deg, #000055, #000077);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.4);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-size: 0.9rem;
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
    .btn-neon-warning {
        background: linear-gradient(135deg, #92400e, #b45309);
        border-color: rgba(245, 158, 11, 0.4);
        color: #fbbf24;
    }
    .btn-neon-warning:hover { box-shadow: 0 0 20px rgba(245, 158, 11, 0.4); color: #fef08a; }
    .btn-cancel {
        background: rgba(100, 116, 139, 0.15);
        border: 1px solid rgba(100, 116, 139, 0.3);
        color: #94a3b8;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-cancel:hover { background: rgba(100, 116, 139, 0.25); color: #FFFFFF; }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    /* Alert */
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
        .edit-hero { padding: 1.25rem; }
        .hero-content { flex-direction: column; gap: 1rem; }
        .user-card { flex-direction: column; text-align: center; }
        .permissions-grid { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column; }
        .form-actions a, .form-actions button { width: 100%; justify-content: center; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="edit-container">
    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Hero Section -->
        <div class="edit-hero">
            <div class="hero-content flex items-center gap-4">
                <a href="<?php echo e(route('admin.admins.index')); ?>" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="hero-icon"><i class="fas fa-key"></i></div>
                <div>
                    <h1 class="hero-title">Chỉnh sửa quyền Admin</h1>
                    <p class="hero-subtitle">Cập nhật quyền hạn cho Admin</p>
                </div>
            </div>
        </div>

        <?php if(session('error')): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <!-- User Info -->
        <div class="user-card">
            <img src="<?php echo e(get_avatar_url($user->avatar)); ?>" class="user-avatar" alt="Avatar">
            <div>
                <div class="user-name"><?php echo e($user->name); ?></div>
                <div class="user-email"><?php echo e($user->email); ?></div>
                <span class="user-badge">
                    <i class="fas fa-user-shield"></i> Admin
                </span>
            </div>
        </div>

        <form action="<?php echo e(route('admin.admins.update-permissions', $user)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <!-- Permissions Selection -->
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-key" style="color: #00E5FF;"></i>
                    <h3 class="form-card-title">Quyền hạn</h3>
                </div>
                <div class="form-card-body">
                    <p style="color: #94a3b8; margin-bottom: 1rem;">Chọn các quyền muốn cấp cho Admin này:</p>
                    
                    <?php $__errorArgs = ['permissions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="alert-error" style="margin-bottom: 1rem;">
                            <i class="fas fa-exclamation-circle"></i>
                            <span><?php echo e($message); ?></span>
                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <?php
                        $currentPermissions = $adminPermission->permissions ?? [];
                    ?>

                    <div class="permissions-grid">
                        <?php $__currentLoopData = $availablePermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="permission-card <?php echo e(in_array($key, old('permissions', $currentPermissions)) ? 'selected' : ''); ?>"
                                 onclick="togglePermission(this, '<?php echo e($key); ?>')">
                                <div class="permission-checkbox">
                                    <input type="checkbox" 
                                           name="permissions[]" 
                                           value="<?php echo e($key); ?>"
                                           id="perm_<?php echo e($key); ?>"
                                           <?php echo e(in_array($key, old('permissions', $currentPermissions)) ? 'checked' : ''); ?>>
                                </div>
                                <div class="permission-icon">
                                    <i class="<?php echo e($permission['icon']); ?>"></i>
                                </div>
                                <div>
                                    <label for="perm_<?php echo e($key); ?>" class="permission-label">
                                        <?php echo e($permission['label']); ?>

                                    </label>
                                    <p class="permission-desc"><?php echo e($permission['description']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="quick-actions">
                        <button type="button" class="btn-quick btn-quick-all" onclick="selectAll()">
                            <i class="fas fa-check-double"></i> Chọn tất cả
                        </button>
                        <button type="button" class="btn-quick btn-quick-none" onclick="deselectAll()">
                            <i class="fas fa-times"></i> Bỏ chọn tất cả
                        </button>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <a href="<?php echo e(route('admin.admins.index')); ?>" class="btn-cancel">
                    <i class="fas fa-times"></i> Hủy
                </a>
                <button type="submit" class="btn-neon btn-neon-warning">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePermission(card, key) {
    const checkbox = card.querySelector('input[type="checkbox"]');
    checkbox.checked = !checkbox.checked;
    card.classList.toggle('selected', checkbox.checked);
}

function selectAll() {
    document.querySelectorAll('.permission-card').forEach(card => {
        const checkbox = card.querySelector('input[type="checkbox"]');
        checkbox.checked = true;
        card.classList.add('selected');
    });
}

function deselectAll() {
    document.querySelectorAll('.permission-card').forEach(card => {
        const checkbox = card.querySelector('input[type="checkbox"]');
        checkbox.checked = false;
        card.classList.remove('selected');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.permission-card').forEach(card => {
        const checkbox = card.querySelector('input[type="checkbox"]');
        card.classList.toggle('selected', checkbox.checked);
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\MarketPlace\resources\views/admin/admins/edit-permissions.blade.php ENDPATH**/ ?>