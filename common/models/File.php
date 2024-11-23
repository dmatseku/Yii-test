<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * File model
 *
 * @property integer $id
 * @property string $filepath
 * @property integer $userId
 */
class File extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%files}}';
    }

    public function fields()
    {
        $fields = parent::fields();

        unset($fields['id'], $fields['user_id']);

        return $fields;
    }

    public function rules()
    {
        return [
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, pdf', 'maxFiles' => 5, 'maxSize' => 1024 * 1024 * 5],
        ];
    }
}