<?php

namespace App\Http\DTOs;

use App\Models\EsportsUser;

/**
 * RegisterDto - Data Transfer Object for registration
 * Chuyển đổi từ EsportsManager.BL.DTOs.RegisterDto
 */
class RegisterDto
{
    public string $username;

    public string $email;

    public string $password;

    public string $confirmPassword;

    public string $fullName;

    public string $role;

    public string $securityQuestion;

    public string $securityAnswer;

    public function __construct(
        string $username = '',
        string $email = '',
        string $password = '',
        string $confirmPassword = '',
        string $fullName = '',
        string $role = EsportsUser::ROLE_PARTICIPANT,
        string $securityQuestion = '',
        string $securityAnswer = ''
    ) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
        $this->fullName = $fullName;
        $this->role = $role;
        $this->securityQuestion = $securityQuestion;
        $this->securityAnswer = $securityAnswer;
    }

    /**
     * Create from request data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['username'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? '',
            $data['confirm_password'] ?? '',
            $data['full_name'] ?? '',
            $data['role'] ?? EsportsUser::ROLE_PARTICIPANT,
            $data['security_question'] ?? '',
            $data['security_answer'] ?? ''
        );
    }

    /**
     * Validate all registration data
     */
    public function validateAll(): array
    {
        $errors = [];

        // Username validation
        if (empty($this->username)) {
            $errors[] = 'Tên đăng nhập không được để trống';
        } elseif (strlen($this->username) < 3) {
            $errors[] = 'Tên đăng nhập phải có ít nhất 3 ký tự';
        } elseif (strlen($this->username) > 50) {
            $errors[] = 'Tên đăng nhập không được vượt quá 50 ký tự';
        } elseif (! preg_match('/^[a-zA-Z0-9_]+$/', $this->username)) {
            $errors[] = 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới';
        }

        // Email validation
        if (empty($this->email)) {
            $errors[] = 'Email không được để trống';
        } elseif (! filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ';
        } elseif (strlen($this->email) > 100) {
            $errors[] = 'Email không được vượt quá 100 ký tự';
        }

        // Password validation
        if (empty($this->password)) {
            $errors[] = 'Mật khẩu không được để trống';
        } elseif (strlen($this->password) < 6) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
        } elseif (! preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $this->password)) {
            $errors[] = 'Mật khẩu phải chứa ít nhất 1 chữ thường, 1 chữ hoa và 1 số';
        }

        // Confirm password validation
        if ($this->password !== $this->confirmPassword) {
            $errors[] = 'Mật khẩu xác nhận không khớp';
        }

        // Full name validation
        if (! empty($this->fullName) && strlen($this->fullName) > 100) {
            $errors[] = 'Họ tên không được vượt quá 100 ký tự';
        }

        // Role validation
        $validRoles = [EsportsUser::ROLE_PARTICIPANT];
        if (! in_array($this->role, $validRoles)) {
            $errors[] = 'Vai trò không hợp lệ';
        }

        // Security question validation
        if (empty($this->securityQuestion)) {
            $errors[] = 'Câu hỏi bảo mật không được để trống';
        } elseif (strlen($this->securityQuestion) > 200) {
            $errors[] = 'Câu hỏi bảo mật không được vượt quá 200 ký tự';
        }

        // Security answer validation
        if (empty($this->securityAnswer)) {
            $errors[] = 'Câu trả lời bảo mật không được để trống';
        } elseif (strlen($this->securityAnswer) < 2) {
            $errors[] = 'Câu trả lời bảo mật phải có ít nhất 2 ký tự';
        }

        return $errors;
    }

    /**
     * Check if data is valid
     */
    public function isValid(): bool
    {
        return empty($this->validateAll());
    }

    /**
     * Convert to array for user creation
     */
    public function toUserArray(): array
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'password_hash' => $this->password, // Will be hashed by model mutator
            'full_name' => $this->fullName ?: null,
            'role' => $this->role,
            'status' => EsportsUser::STATUS_ACTIVE, // Tự động kích hoạt
            'security_question' => $this->securityQuestion,
            'security_answer' => $this->securityAnswer, // Will be hashed by model mutator
            'is_email_verified' => false,
        ];
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'confirm_password' => $this->confirmPassword,
            'full_name' => $this->fullName,
            'role' => $this->role,
            'security_question' => $this->securityQuestion,
            'security_answer' => $this->securityAnswer,
        ];
    }
}
