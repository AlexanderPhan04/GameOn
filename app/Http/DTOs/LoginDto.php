<?php

namespace App\Http\DTOs;

/**
 * LoginDto - Data Transfer Object for login
 * Chuyển đổi từ EsportsManager.BL.DTOs.LoginDto
 */
class LoginDto
{
    public string $username;

    public string $password;

    public function __construct(string $username = '', string $password = '')
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Create from request data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['username'] ?? '',
            $data['password'] ?? ''
        );
    }

    /**
     * Validate login data
     */
    public function validate(): array
    {
        $errors = [];

        if (empty($this->username)) {
            $errors[] = 'Tên đăng nhập không được để trống';
        }

        if (empty($this->password)) {
            $errors[] = 'Mật khẩu không được để trống';
        }

        return $errors;
    }

    /**
     * Check if data is valid
     */
    public function isValid(): bool
    {
        return empty($this->validate());
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}
