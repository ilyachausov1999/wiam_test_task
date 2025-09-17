<?php

return [
    'enableProfiling' => false,
    'enableLogging' => false,

    'class' => 'yii\db\Connection',
    'dsn' => getenv('DB_DSN') ?: 'pgsql:host=postgres;dbname=loans',
    'username' => getenv('DB_USER') ?: 'test',
    'password' =>  getenv('DB_PASSWORD') ?: 'test',
    'charset' => getenv('DB_CHARSET') ?:'utf8',
];