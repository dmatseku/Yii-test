<?php

namespace backend\controllers;

use backend\models\Forms\CreateLoanForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class LoanController extends ActiveController
{
    protected const array CLIENT_ACTIONS = [
        'view',
        'new',
    ];

    /**
     * {@inheritdoc}
     */
    public $modelClass = 'backend\models\Loan';

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
                    'actions' => ['index', 'view', 'new', 'update', 'delete', 'options'],
                    'roles' => ['@'],
                ],
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
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
        if (in_array($action, static::CLIENT_ACTIONS)) {
            if (!Yii::$app->user->can("hasFullPower") &&
                !Yii::$app->user->can("dataBelongsToClient", ["user_id" => $model->user_id])) {
                throw new \yii\web\ForbiddenHttpException("You can only $action loans that you've uploaded.");
            }
        } elseif (!Yii::$app->user->can("hasFullPower")) {
            throw new \yii\web\ForbiddenHttpException();
        }
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionNew(): array
    {
        $model = Yii::$container->get(CreateLoanForm::class);

        if ($model->load(Yii::$app->request->post()) && $loan = $model->createLoan()) {
            return [
                'success' => true,
                'message' => 'Loan created successfully.',
                'data' => [
                    'loan_id' => $loan->id
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