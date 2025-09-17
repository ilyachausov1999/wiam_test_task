<?php

namespace app\services;

use app\DTOs\requests\LoanRequestDto;
use app\DTOs\responses\LoanResponseDto;

interface LoanServiceInterface
{
    /**
     *
     * @param LoanRequestDto $dto
     * @return LoanResponseDto
     */
    public function createLoanRequest(LoanRequestDto $dto): LoanResponseDto;
}
