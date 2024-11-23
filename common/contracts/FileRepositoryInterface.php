<?php

namespace common\contracts;

use common\models\File;

interface FileRepositoryInterface
{
    public function create($filepath, $userId);

    public function get($value, $field = "id");

    public function getFor($userId);

    public function save(File $file);

    public function delete(File $file);
}