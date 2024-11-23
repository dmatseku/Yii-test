<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;

class RbacController extends Controller
{
    public function __construct(
        $id,
        $module,
        protected \common\contracts\UserRepositoryInterface $userRepository
    ) {
        parent::__construct($id, $module);
    }

    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $clientRule = Yii::$container->get(\common\rbac\DataBelongsToClient::class);
        $auth->add($clientRule);
        echo "added client own data rule" . PHP_EOL;

        $dataBelongsToClientPermission = $auth->createPermission('dataBelongsToClient');
        $dataBelongsToClientPermission->description = 'Get data of own profile';
        $dataBelongsToClientPermission->ruleName = $clientRule->name;
        $auth->add($dataBelongsToClientPermission);
        echo "created dataBelongsToClient" . PHP_EOL;

        $client = $auth->createRole('client');
        $auth->add($client);
        echo "created client role" . PHP_EOL;

        $auth->addChild($client, $dataBelongsToClientPermission);
        echo "dataBelongsToClient assigned to client" . PHP_EOL;

        $hasFullPowerPermission = $auth->createPermission('hasFullPower');
        $hasFullPowerPermission->description = 'allowed everything';
        $auth->add($hasFullPowerPermission);
        echo "created hasFullPower" . PHP_EOL;
        $auth->addChild($dataBelongsToClientPermission, $hasFullPowerPermission);
        echo "linked dataBelongsToClientPermission with hasFullPowerPermission" . PHP_EOL;

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        echo "created admin role" . PHP_EOL;

        $auth->addChild($admin, $client);
        $auth->addChild($admin, $hasFullPowerPermission);
        echo "assigned everything to admin role" . PHP_EOL;
    }

    public function actionAdmin($userId)
    {
        $user = $this->userRepository->get($userId);

        if ($user) {
            Yii::$app->authManager->assign(Yii::$app->authManager->getRole('admin'), $user->id);
            echo "assigned " . $user->firstname . " " . $user->lastname . " to admin role" . PHP_EOL;
        } else {
            echo "user not found" . PHP_EOL;
        }
    }
}