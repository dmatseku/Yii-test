<?php

namespace common\models\Forms;

use common\contracts\UserRepositoryInterface;
use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $passport_number;
    public $firstname;
    public $lastname;
    public $date_of_birth;
    public $passport_expiry_date;
    public $password;

    public function __construct(
        protected UserRepositoryInterface $userRepository,
        $config = []
    ) {
        parent::__construct($config);
    }

    public function formName()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['passport_number', 'trim'],
            ['passport_number', 'required'],
            ['passport_number', 'unique', 'targetClass' => \common\models\User::class, 'message' => 'This user already exists.'],
            ['passport_number', 'string', 'min' => 10, 'max' => 60],

            ['firstname', 'trim'],
            ['firstname', 'required'],
            ['firstname', 'string', 'min' => 3, 'max' => 60],

            ['lastname', 'trim'],
            ['lastname', 'required'],
            ['lastname', 'string', 'min' => 3, 'max' => 60],

            ['date_of_birth', 'required'],
            ['date_of_birth', 'date', 'format' => 'YYYY-MM-DD', 'message' => 'invalid date of birth'],

            ['passport_expiry_date', 'required'],
            ['passport_expiry_date', 'date', 'format' => 'YYYY-MM-DD', 'message' => 'invalid expiry date'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->passport_number = $this->passport_number;
        $user->firstname = $this->firstname;
        $user->lastname = $this->lastname;
        $user->date_of_birth = $this->date_of_birth;
        $user->passport_expiry_date = $this->passport_expiry_date;
        $user->access_token = Yii::$app->security->generateRandomString();
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword($this->password);

        if ($this->userRepository->save($user)) {
            Yii::$app->authManager->assign(Yii::$app->authManager->getRole('client'), $user->id);
            return $user;
        }

        return false;
    }
}
