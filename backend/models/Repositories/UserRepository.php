<?php

namespace backend\models\Repositories;

use backend\contracts\UserRepositoryInterface;
use backend\models\User;

class UserRepository implements UserRepositoryInterface
{

    /**
     * @param mixed $value
     * @param string $field
     * @return User
     */
    public function get(mixed $value, string $field = "id"): User
    {
        return User::findOne([$field => $value, 'status' => User::STATUS_ACTIVE]);
    }

    /**
     * @param User $user
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save(User $user): bool
    {
        return $user->save();
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }
}