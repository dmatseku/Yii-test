<?php

namespace backend\models;

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
    public static function tableName(): string
    {
        return '{{%files}}';
    }

    /**
     * {@inheritdoc}
     */
    public function fields(): array
    {
        $fields = parent::fields();

        unset($fields['id'], $fields['user_id']);

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['filepath', 'string', 'max' => 255, 'min' => 3],
        ];
    }
}