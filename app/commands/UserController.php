<?php

declare(strict_types=1);

namespace app\commands;

use Yii;
use yii\base\Exception;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\User;

class UserController extends Controller
{
    /**
     * Создание нового пользователя через команду
     *
     * Пример php yii user/create "john_doe" "john.doe@example.com" "secure_password123"
     *
     * @param string $name User name
     * @param string $email Email
     * @param string $password password
     * @return int Exit code
     * @throws Exception
     */
    public function actionCreate(string $name, string $email, string $password): int
    {
        $time = time();

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
        $user->created_at = $time;
        $user->updated_at = $time;

        if ($user->save()) {
            Yii::info("Пользователь {$name} создан успешно.");
            return ExitCode::OK;
        } else {
            $errorMsg = "Ошибка при создании пользователя:\n";
            foreach ($user->errors as $attribute => $errors) {
                $errorMsg .= " - {$attribute}: " . implode(', ', $errors) . "\n";
            }
            Yii::error($errorMsg);
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}
