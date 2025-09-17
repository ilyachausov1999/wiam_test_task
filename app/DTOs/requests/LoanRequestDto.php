<?php

declare(strict_types=1);

namespace app\DTOs\requests;
class LoanRequestDto
{
    public function __construct(
        public int $userId,
        public int $amount,
        public int $term
    ) {}

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getTerm(): int
    {
        return $this->term;
    }
}