<?php

namespace backend\controllers;

use backend\models\Forms\FileUploadForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\UploadedFile;

class FileController extends ActiveController
{
    protected const array CLIENT_ACTIONS = [
        'view',
        'upload'
    ];

    /**
     * {@inheritdoc}
     */
    public $modelClass = 'backend\models\File';

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
                    'actions' => ['index', 'view', 'create', 'update', 'delete', 'upload'],
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
        if ($model !== null && in_array($action, static::CLIENT_ACTIONS)) {
            if (!Yii::$app->user->can("hasFullPower") &&
                !Yii::$app->user->can("dataBelongsToClient", ["user_id" => $model->user_id])) {
                throw new \yii\web\ForbiddenHttpException("You can only $action files that you've uploaded.");
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
    public function actionUpload(): array
    {
        $model = Yii::$container->get(FileUploadForm::class);

        $model->files = UploadedFile::getInstancesByName('files');

        if ($model->upload()) {
            return [
                'success' => true,
                'message' => 'Files have been uploaded.',
            ];
        }

        Yii::$app->response->statusCode = 401;
        return [
            'success' => false,
            'errors' => $model->errors,
        ];
    }
}