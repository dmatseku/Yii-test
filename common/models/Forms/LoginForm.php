<?php

namespace common\models\Forms;

use common\contracts\UserRepositoryInterface;
use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $passport_number;
    public $password;
    public $rememberMe = true;

    private $_user;

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
            // username and password are both required
            [['passport_number', 'password'], 'required', 'message' => 'both fields are required.'],
            ['passport_number', 'string', 'min' => 10],

            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided passport number and password.
     *
     * @return User|bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $token = Yii::$app->security->generateRandomString();
            $this->getUser()->access_token = $token;
            $this->userRepository->save($this->getUser());
            return $this->getUser();
        }
        
        return false;
    }

    /**
     * Finds user by [[passport_number]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = $this->userRepository->get($this->passport_number, 'passport_number');
        }

        return $this->_user;
    }
}
