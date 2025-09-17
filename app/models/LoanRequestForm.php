<?php

declare(strict_types=1);

namespace app\models;

use app\DTOs\requests\LoanRequestDto;
use yii\base\Model;

class LoanRequestForm extends Model
{
    public $user_id;
    public $amount;
    public $term;

    public function rules(): array
    {
        return [
            [['user_id', 'amount', 'term'], 'required'],
            [['user_id', 'amount', 'term'], 'integer', 'min' => 1],
            ['user_id', 'validateUserExists']
        ];
    }

    public function validateUserExists($attribute): void
    {
        $userExists = User::find()->where(['id' => $this->$attribute])->exists();

        if (!$userExists) {
            $this->addError($attribute, 'User does not exist');
        }
    }

    public function validateData(): bool
    {
        return $this->validate();
    }

    public function getDto(): LoanRequestDto
    {
        return new LoanRequestDto(
            userId: $this->user_id,
            amount: $this->amount,
            term:   $this->term,
        );
    }

    public function getErrorsAsArray(): array
    {
        return $this->getErrors();
    }
}