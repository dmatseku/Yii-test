<?php

namespace common\rbac;

class DataBelongsToClient extends \yii\rbac\Rule
{
    public $name = 'DataBelongsToClient';

    /**
     * @inheritDoc
     */
    public function execute($user, $item, $params)
    {
        return isset($params['user_id']) && $params['user_id'] == $user;
    }
}