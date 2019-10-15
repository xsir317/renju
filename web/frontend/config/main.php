<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    "timezone" => "Asia/Shanghai",
    'sourceLanguage' => 'en-US',
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'games' => [
            'class' => 'frontend\modules\games\Module'
        ],
        'records' => [
            'class' => 'frontend\modules\records\Module'
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\Player',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
            'class' => 'yii\web\CacheSession',
            'timeout' => 864000,
            'useCookies' => true,
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/languages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/game/<id:\d+>' => '/games/games/game',
                '/games/history/<player_id:\d+>' => '/games/games/history',
                '/about.html' => '/site/about',
            ],
        ],
        'cache' => [
            'class' => 'common\components\RedisCache',
            'redis' => [
                'database' => 0,
                'host' => '127.0.0.1',
                'port' => 6379,
                'prefix' => 'cache::',
            ]
        ]
    ],
    'params' => $params,
];
