<?php

namespace App\Http\DTOs;

/**
 * UserDto - Data Transfer Object for user display
 * Chuyển đổi từ EsportsManager.BL.DTOs.UserDto
 */
class UserDto
{
    public int $id;

    public string $username;

    public string $email;

    public ?string $fullName;

    public string $role;

    public string $status;

    public ?string $lastLoginAt;

    public string $createdAt;

    public function __construct(
        int $id,
        string $username,
        string $email,
        ?string $fullName,
        string $role,
        string $status,
        ?string $lastLoginAt = null,
        string $createdAt = ''
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->fullName = $fullName;
        $this->role = $role;
        $this->status = $status;
        $this->lastLoginAt = $lastLoginAt;
        $this->createdAt = $createdAt;
    }

    /**
     * Create from EsportsUser model
     */
    public static function fromModel($user): self
    {
        return new self(
            $user->user_id,
            $user->username,
            $user->email,
            $user->full_name,
            $user->role,
            $user->status,
            $user->last_login_at?->format('Y-m-d H:i:s'),
            $user->created_at->format('Y-m-d H:i:s')
        );
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'full_name' => $this->fullName,
            'role' => $this->role,
            'status' => $this->status,
            'last_login_at' => $this->lastLoginAt,
            'created_at' => $this->createdAt,
        ];
    }

    /**
     * Get role display name in Vietnamese
     */
    public function getRoleDisplayName(): string
    {
        return match ($this->role) {
            'Admin', 'admin', 'super_admin' => 'Quản trị viên',
            'Participant', 'participant' => 'Người tham gia',
            default => 'Không xác định'
        };
    }

    /**
     * Get status display name in Vietnamese
     */
    public function getStatusDisplayName(): string
    {
        return match ($this->status) {
            'Active' => 'Hoạt động',
            'Suspended' => 'Tạm khóa',
            'Inactive' => 'Không hoạt động',
            'Pending' => 'Chờ duyệt',
            'Deleted' => 'Đã xóa',
            default => 'Không xác định'
        };
    }
}
