<?php

namespace App\Http\Results;

/**
 * AuthenticationResult - Result object for authentication operations
 * Chuyển đổi từ EsportsManager.BL.Models.AuthenticationResult
 */
class AuthenticationResult
{
    public bool $isSuccess;

    public string $message;

    public ?int $userId;

    public ?string $username;

    public ?string $role;

    private function __construct(
        bool $isSuccess,
        string $message,
        ?int $userId = null,
        ?string $username = null,
        ?string $role = null
    ) {
        $this->isSuccess = $isSuccess;
        $this->message = $message;
        $this->userId = $userId;
        $this->username = $username;
        $this->role = $role;
    }

    /**
     * Create successful authentication result
     */
    public static function success(int $userId, string $username, string $role): self
    {
        return new self(
            true,
            'Đăng nhập thành công',
            $userId,
            $username,
            $role
        );
    }

    /**
     * Create failed authentication result
     */
    public static function failure(string $message): self
    {
        return new self(false, $message);
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        $result = [
            'success' => $this->isSuccess,
            'message' => $this->message,
        ];

        if ($this->isSuccess) {
            $result['user'] = [
                'id' => $this->userId,
                'username' => $this->username,
                'role' => $this->role,
            ];
        }

        return $result;
    }

    /**
     * Get role display name in Vietnamese
     */
    public function getRoleDisplayName(): ?string
    {
        if (! $this->role) {
            return null;
        }

        return match ($this->role) {
            'Admin', 'admin', 'super_admin' => 'Quản trị viên',
            'Participant', 'participant' => 'Người tham gia',
            default => 'Không xác định'
        };
    }
}
