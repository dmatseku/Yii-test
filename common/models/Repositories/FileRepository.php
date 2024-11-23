<?php

namespace common\models\Repositories;

use common\contracts\FileRepositoryInterface;
use common\models\File;

class FileRepository implements FileRepositoryInterface
{
    public function create($filepath, $userId)
    {
        return new File(['filepath' => $filepath, 'user_id' => $userId]);
    }

    public function get($value, $field = "id")
    {
        return File::findOne([$field => $value]);
    }

    public function getFor($userId)
    {
        return File::findAll(['user_id' => $userId]);
    }

    public function save(File $file)
    {
        $file->save();
    }

    public function delete(File $file)
    {
        $file->delete();
    }
}