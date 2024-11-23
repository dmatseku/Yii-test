<?php

namespace backend\controllers;

use backend\models\Forms\LoginForm;
use backend\models\Forms\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
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

    /**
     * {@inheritdoc}
     */
    public $modelClass = 'backend\models\User';

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
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


    /**
     * {@inheritdoc}
     */
    public function checkAccess($action, $model = null, $params = []): void
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

    /**
     * @return array
     */
    public function actionLogout(): array
    {
        Yii::$app->user->identity->clear_access_token();

        return ['success' => true];
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionLogin(): array
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

        Yii::$app->response->statusCode = 401;
        return [
            'success' => false,
            'errors' => $model->errors,
        ];
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionSignup(): array
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

        Yii::$app->response->statusCode = 401;
        return [
            'success' => false,
            'errors' => $model->errors,
        ];
    }
}