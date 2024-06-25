<?php

return [
    'class' => \yii\db\Connection::class,
    'dsn' => env('DB_DSN'),
    'username' => env('DB_USERNAME'),
    'password' =>  env('DB_PASSWORD'),
    'charset' => 'utf8',
];
