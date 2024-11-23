<?php

namespace backend\controllers;

use common\models\Forms\ClientInfoForm;
use common\models\Forms\LoginForm;
use common\models\Forms\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;

class AccountController extends ActiveController
{
    protected const array CLIENT_ACTIONS = [
        'view'
    ];

    protected const array LOGIN_ACTIONS = [
        'login',
        'logout',
        'signup',
    ];

    public $modelClass = 'common\models\User';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['logout', 'index', 'view', 'create', 'update', 'delete'],
                    'roles' => ['@'],
                ],
                [
                    'allow' => true,
                    'actions' => ['login', 'signup', 'options'],
                    'roles' => ['?'],
                ],
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['login', 'signup'],
        ];
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($model !== null && in_array($action, static::CLIENT_ACTIONS)) {
            if (!Yii::$app->user->can("hasFullPower") &&
                !Yii::$app->user->can("dataBelongsToClient", ["user_id" => $model->id])) {
                throw new \yii\web\ForbiddenHttpException("You can only $action profile that you've created.");
            }
        } elseif (!in_array($action, static::LOGIN_ACTIONS)) {
            if (!Yii::$app->user->can("hasFullPower")) {
                throw new \yii\web\ForbiddenHttpException();
            }
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->identity->clear_access_token();

        return ['success' => true];
    }

    public function actionLogin()
    {
        $model = Yii::$container->get(LoginForm::class);

        if ($model->load(Yii::$app->request->post()) && $user = $model->login()) {
            return [
                'success' => true,
                'message' => 'Logged in successfully.',
                'data' => [
                    'user_id' => $user->id,
                    'token' => $user->access_token,
                ]
            ];
        }

        return [
            'success' => false,
            'errors' => $model->errors,
        ];
    }

    public function actionSignup()
    {
        $model = Yii::$container->get(SignupForm::class);

        if ($model->load(Yii::$app->request->post()) && $user = $model->signup()) {
            return [
                'success' => true,
                'message' => 'Account created successfully.',
                'data' => [
                    'user_id' => $user->id,
                    'token' => $user->access_token,
                ]
            ];
        }

        return [
            'success' => false,
            'errors' => $model->errors,
        ];
    }
}