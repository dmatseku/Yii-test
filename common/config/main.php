<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
    'container' => [
        'definitions' => [
            \backend\contracts\UserRepositoryInterface::class => \backend\models\Repositories\UserRepository::class,
            \backend\contracts\FileRepositoryInterface::class => \backend\models\Repositories\FileRepository::class,
            \backend\contracts\LoanRepositoryInterface::class => \backend\models\Repositories\LoanRepository::class,
        ]
    ]
];
