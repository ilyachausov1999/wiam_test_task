<?php

declare(strict_types=1);

namespace app\DTOs\responses;

use app\models\Request;

readonly class LoanResponseDto
{
    public function __construct(
        private bool     $success,
        private ?Request $result
    ) {}

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getResult(): ?Request
    {
        return $this->result;
    }
}