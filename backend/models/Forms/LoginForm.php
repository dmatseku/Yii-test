<?php

namespace backend\models\Forms;

use backend\contracts\UserRepositoryInterface;
use backend\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;

class LoginForm extends Model
{
    public string $passport_number;
    public string $password;
    public bool $rememberMe = true;

    private ?User $_user;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param $config
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
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
     * @param string $attribute
     * @param array $params
     */
    public function validatePassword(string $attribute, array $params): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * @return User|bool
     * @throws Exception
     */
    public function login(): bool|User
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
     * @return User|null
     */
    protected function getUser(): ?User
    {
        if ($this->_user === null) {
            $this->_user = $this->userRepository->get($this->passport_number, 'passport_number');
        }

        return $this->_user;
    }
}
