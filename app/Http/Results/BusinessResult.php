<?php

namespace App\Http\Results;

/**
 * BusinessResult - Generic result object for business operations
 * Chuyển đổi từ EsportsManager.BL.Utilities.BusinessResult
 */
class BusinessResult
{
    public bool $isSuccess;

    public string $message;

    public mixed $data;

    private function __construct(bool $isSuccess, string $message = '', mixed $data = null)
    {
        $this->isSuccess = $isSuccess;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Create successful result
     */
    public static function success(mixed $data = null, string $message = ''): self
    {
        return new self(true, $message, $data);
    }

    /**
     * Create failed result
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

        if ($this->data !== null) {
            $result['data'] = $this->data;
        }

        return $result;
    }

    /**
     * Convert to JSON response
     */
    public function toJsonResponse(): array
    {
        return $this->toArray();
    }
}
