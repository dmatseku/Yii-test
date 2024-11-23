<?php

namespace common\models\Forms;

use common\contracts\FileRepositoryInterface;
use common\contracts\UserRepositoryInterface;
use Yii;
use yii\base\Model;

class FileUploadForm extends Model
{
    public $files;

    private $_user;

    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected FileRepositoryInterface $fileRepository,
        $config = []
    ) {
        parent::__construct($config);
    }

    public function formName()
    {
        return '';
    }

    public function rules()
    {
        return [
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, pdf', 'maxFiles' => 5, 'maxSize' => 1024 * 1024 * 5],
            ['files', 'required'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->files as $file) {
                $filename = $this->getUser()->id . '_' . $file->baseName . '.' . $file->extension;
                $fileModel = $this->fileRepository->create(Yii::getAlias('@web/uploads/') . $filename, $this->getUser()->id);

                $file->saveAs(Yii::getAlias('@webroot/uploads/') . $filename);
                $this->fileRepository->save($fileModel);
            }
            return true;
        } else {
            return false;
        }
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Yii::$app->user->identity;
        }

        return $this->_user;
    }
}