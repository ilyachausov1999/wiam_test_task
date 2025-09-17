<?php

declare(strict_types=1);

namespace app\models;

use app\models\Enums\LoanStatusEnum;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $user_id
 * @property int $amount
 * @property int $term
 * @property int $status
 * @property int $created_at
 * @property int|null $processed_at
 */
class Request extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%requests}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['user_id', 'amount', 'term'], 'required'],
            [['user_id', 'amount', 'term', 'status'], 'integer'],
            [['amount', 'term'], 'integer', 'min' => 0],
            [['status'], 'default', 'value' => LoanStatusEnum::PENDING->value],
            [['status'], 'in', 'range' => LoanStatusEnum::getStatusCodes()],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']]
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
