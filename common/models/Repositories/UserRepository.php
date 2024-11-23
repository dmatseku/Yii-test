<?php

namespace common\models\Repositories;

use common\contracts\UserRepositoryInterface;
use common\models\User;

class UserRepository implements UserRepositoryInterface
{

    public function get($value, $field = "id")
    {
        return User::findOne([$field => $value, 'status' => User::STATUS_ACTIVE]);
    }

    public function save(User $user)
    {
        return $user->save();
    }

    public function delete(User $user)
    {
        return $user->delete();
    }
}