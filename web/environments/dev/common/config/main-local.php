<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=renju',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'redis' => [
            'class' => 'common\components\RedisConnection',
            'database' => 1,
            'host' => '127.0.0.1',
            'port' => 6379,
            'password' => '1234',
            'prefix' => 'kv::',
        ],
        'queue' => [
            'class' => 'common\components\RedisConnection',
            'database' => 1,
            'host' => '127.0.0.1',
            'port' => 6379,
            'password' => '1234',
            'prefix' => 'kv::',
        ],
        'cache' => [
            'class' => 'common\components\RedisCache',
            'redis' => [
                'database' => 0,
                'host' => '127.0.0.1',
                'port' => 6379,
                'password' => '1234',
                'prefix' => 'cache::',
            ]
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
