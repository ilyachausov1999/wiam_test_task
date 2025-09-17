<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Model User
 *
 * @property int $id;
 * @property string $name;
 * @property string $email;
 * @property string $password_hash;
 * @property int $created_at;
 * @property int $updated_at;
 */
class User extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%users}}';
    }

    public function rules(): array
    {
        return [
            [['name', 'email', 'password_hash'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['email'], 'string', 'max' => 128],
            [['password_hash'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['name', 'email'], 'unique'],
        ];
    }
}
