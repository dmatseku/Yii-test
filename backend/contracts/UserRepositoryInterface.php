<?php

namespace backend\contracts;

use backend\models\User;

interface UserRepositoryInterface
{
    /**
     * @param mixed $value
     * @param string $field
     * @return User
     */
    public function get(mixed $value, string $field = "id"): User;

    /**
     * @param User $user
     * @return bool
     */
    public function save(User $user): bool;

    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool;
}