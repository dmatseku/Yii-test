<?php

namespace backend\models\Forms;

use backend\contracts\FileRepositoryInterface;
use backend\contracts\UserRepositoryInterface;
use backend\models\User;
use Yii;
use yii\base\Model;

class FileUploadForm extends Model
{
    public array $files;

    private ?User $_user = null;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param FileRepositoryInterface $fileRepository
     * @param $config
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected FileRepositoryInterface $fileRepository,
        $config = []
    ) {
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, pdf', 'maxFiles' => 5, 'maxSize' => 1024 * 1024 * 5],
            ['files', 'required'],
        ];
    }

    /**
     * @return bool
     */
    public function upload(): bool
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

    /**
     * @return ?User
     */
    protected function getUser(): ?User
    {
        if ($this->_user === null) {
            $this->_user = Yii::$app->user->identity;
        }

        return $this->_user;
    }
}