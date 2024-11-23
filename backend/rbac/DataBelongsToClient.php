<?php

namespace backend\rbac;

class DataBelongsToClient extends \yii\rbac\Rule
{
    /**
     * @inheritDoc
     */
    public $name = 'DataBelongsToClient';

    /**
     * @inheritDoc
     */
    public function execute($user, $item, $params): bool
    {
        return isset($params['user_id']) && $params['user_id'] == $user;
    }
}