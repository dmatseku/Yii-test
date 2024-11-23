<?php

namespace backend\models;

use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property integer $id
 * @property string $passport_number
 * @property string $firstname
 * @property string $lastname
 * @property string $date_of_birth
 * @property string $passport_expiry_date
 * @property string $access_token
 * @property string $password_hash
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const int STATUS_DELETED = 0;
    public const int STATUS_INACTIVE = 9;
    public const int STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function fields(): array
    {
        $fields = parent::fields();

        unset($fields['password_hash'], $fields['created_at'], $fields['updated_at']);

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['firstname', 'trim'],
            ['firstname', 'string', 'min' => 3, 'max' => 60],

            ['lastname', 'trim'],
            ['lastname', 'string', 'min' => 3, 'max' => 60],

            ['date_of_birth', 'date', 'format' => 'YYYY-MM-DD', 'message' => 'invalid date of birth'],

            ['passport_expiry_date', 'date', 'format' => 'YYYY-MM-DD', 'message' => 'invalid expiry date'],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): User|IdentityInterface|null
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): User|IdentityInterface|null
    {
        if ($token == null) {
            return null;
        }

        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param string $passport_number
     * @return static|null
     */
    public static function findByPassportNumber(string $passport_number): null|static
    {
        return static::findOne(['passport_number' => $passport_number, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): mixed
    {
        return $this->getPrimaryKey();
    }

    /**
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @param string $password
     * @throws Exception
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @return mixed
     * @throws \yii\base\NotSupportedException
     */
    public function getAuthKey()
    {
        throw new \yii\base\NotSupportedException();
    }

    /**
     * @param $authKey
     * @return mixed
     * @throws \yii\base\NotSupportedException
     */
    public function validateAuthKey($authKey)
    {
        throw new \yii\base\NotSupportedException();
    }

    /**
     * @throws \yii\db\Exception
     */
    public function clear_access_token(): void
    {
        $this->access_token = null;
        $this->save();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles(): \yii\db\ActiveQuery
    {
        return $this->hasMany(File::class, ['user_id' => 'id']);
    }
}
