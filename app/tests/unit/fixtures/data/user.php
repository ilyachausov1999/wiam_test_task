<?php

use yii\base\Security;

$security = new Security();
$time = time();

return [
    [
        'name' => 'admin',
        'email' => 'admin@example.com',
        'password_hash' => $security->generatePasswordHash('admin123'),
        'created_at' => $time,
        'updated_at' => $time,
    ],
    [
        'name' => 'ivanov',
        'email' => 'ivanov@example.com',
        'password_hash' => $security->generatePasswordHash('ivanov123'),
        'created_at' => $time,
        'updated_at' => $time,
    ],
    [
        'name' => 'petrov',
        'email' => 'petrov@example.com',
        'password_hash' => $security->generatePasswordHash('petrov123'),
        'created_at' => $time,
        'updated_at' => $time,
    ],
];