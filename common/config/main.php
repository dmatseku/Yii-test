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
            \common\contracts\UserRepositoryInterface::class => \common\models\Repositories\UserRepository::class,
            \common\contracts\FileRepositoryInterface::class => \common\models\Repositories\FileRepository::class,
            \common\contracts\LoanRepositoryInterface::class => \common\models\Repositories\LoanRepository::class,
        ]
    ]
];
