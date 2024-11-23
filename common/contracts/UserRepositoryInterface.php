<?php

namespace common\contracts;

use common\models\User;

interface UserRepositoryInterface
{
    public function get($value, $field = "id");

    public function save(User $user);

    public function delete(User $user);
}