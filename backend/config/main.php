<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl' => null,
            'enableSession' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['account'],
                    'extraPatterns' => [
                        'POST signup' => 'signup',
                        'POST login' => 'login',
                        'POST logout' => 'logout',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['file'],
                    'extraPatterns' => [
                        'POST upload' => 'upload',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['loan'],
                    'except' => ['create'],
                    'extraPatterns' => [
                        'POST new' => 'new',
                    ],
                ]
            ],
        ],
    ],
    'params' => $params,
];
