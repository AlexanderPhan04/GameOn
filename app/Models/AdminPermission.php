<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminPermission extends Model
{
    protected $fillable = [
        'user_id',
        'permissions',
        'granted_by',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Danh sách tất cả quyền có thể cấp cho Admin
     */
    public const AVAILABLE_PERMISSIONS = [
        'manage_users' => [
            'label' => 'Quản lý người dùng',
            'description' => 'Xem, sửa, khóa/mở khóa tài khoản người dùng',
            'icon' => 'fas fa-users',
        ],
        'manage_teams' => [
            'label' => 'Quản lý đội',
            'description' => 'Xem, sửa, xóa đội và thành viên',
            'icon' => 'fas fa-users-cog',
        ],
        'manage_tournaments' => [
            'label' => 'Quản lý giải đấu',
            'description' => 'Tạo, sửa, xóa giải đấu',
            'icon' => 'fas fa-trophy',
        ],
        'manage_games' => [
            'label' => 'Quản lý game',
            'description' => 'Thêm, sửa, xóa game',
            'icon' => 'fas fa-gamepad',
        ],
        'manage_posts' => [
            'label' => 'Quản lý bài viết',
            'description' => 'Xem, sửa, xóa bài viết của người dùng',
            'icon' => 'fas fa-newspaper',
        ],
        'manage_honor' => [
            'label' => 'Quản lý Honor/Vote',
            'description' => 'Tạo, quản lý các sự kiện bình chọn',
            'icon' => 'fas fa-award',
        ],
        'manage_marketplace' => [
            'label' => 'Quản lý Marketplace',
            'description' => 'Quản lý sản phẩm, đơn hàng',
            'icon' => 'fas fa-store',
        ],
        'manage_donations' => [
            'label' => 'Quản lý Donations',
            'description' => 'Xem, quản lý các khoản quyên góp',
            'icon' => 'fas fa-hand-holding-heart',
        ],
        'view_reports' => [
            'label' => 'Xem báo cáo',
            'description' => 'Xem thống kê, báo cáo hệ thống',
            'icon' => 'fas fa-chart-bar',
        ],
        'manage_settings' => [
            'label' => 'Cài đặt hệ thống',
            'description' => 'Thay đổi cài đặt hệ thống',
            'icon' => 'fas fa-cogs',
        ],
    ];

    /**
     * Admin user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Người cấp quyền (Super Admin)
     */
    public function granter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    /**
     * Kiểm tra có quyền cụ thể không
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * Thêm quyền
     */
    public function grantPermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->update(['permissions' => $permissions]);
        }
    }

    /**
     * Xóa quyền
     */
    public function revokePermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];
        $permissions = array_filter($permissions, fn($p) => $p !== $permission);
        $this->update(['permissions' => array_values($permissions)]);
    }

    /**
     * Cập nhật tất cả quyền
     */
    public function syncPermissions(array $permissions): void
    {
        // Chỉ giữ lại các quyền hợp lệ
        $validPermissions = array_intersect($permissions, array_keys(self::AVAILABLE_PERMISSIONS));
        $this->update(['permissions' => array_values($validPermissions)]);
    }

    /**
     * Lấy danh sách quyền với thông tin chi tiết
     */
    public function getPermissionsWithDetails(): array
    {
        $result = [];
        foreach ($this->permissions ?? [] as $permission) {
            if (isset(self::AVAILABLE_PERMISSIONS[$permission])) {
                $result[$permission] = self::AVAILABLE_PERMISSIONS[$permission];
            }
        }
        return $result;
    }
}
