<?php

namespace backend\models;

use yii\db\ActiveRecord;

/**
 * Loan model
 *
 * @property integer $id
 * @property integer $amount
 * @property integer $term
 * @property string $purpose
 * @property integer $income
 * @property integer status
 * @property integer $userId
 */
class Loan extends ActiveRecord
{
    public const array STATUSES = [
        'approved' => 1,
        'pending' => 2,
        'rejected' => 3,
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%loans}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['status', 'default', 'value' => static::STATUSES['pending']],
            ['status', 'compare', 'compareValue' => static::STATUSES['pending'], 'operator' => '==', 'type' => 'integer'],
        ];
    }
}