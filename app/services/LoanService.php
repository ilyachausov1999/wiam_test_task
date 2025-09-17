<?php

declare(strict_types=1);

namespace app\services;

use app\DTOs\requests\LoanRequestDto;
use app\DTOs\responses\LoanResponseDto;
use app\models\Enums\LoanStatusEnum;
use app\models\Request;
use yii\db\Exception as DbException;

class LoanService implements LoanServiceInterface
{
    /**
     *
     * @param LoanRequestDto $dto
     * @return LoanResponseDto
     * @throws DbException
     */
    public function createLoanRequest(LoanRequestDto $dto): LoanResponseDto
    {
        $request = new Request();
        $request->user_id = $dto->getUserId();
        $request->amount = $dto->getAmount();
        $request->term = $dto->getTerm();
        $request->status = LoanStatusEnum::PENDING->value;
        $request->created_at = time();
        $request->processed_at = time();

        $success = $request->save();

        return new LoanResponseDto(
            $success,
            $request
        );
    }
}
