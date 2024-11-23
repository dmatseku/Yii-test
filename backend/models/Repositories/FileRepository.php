<?php

namespace backend\models\Repositories;

use backend\contracts\FileRepositoryInterface;
use backend\models\File;

class FileRepository implements FileRepositoryInterface
{
    /**
     * @param string $filepath
     * @param int $userId
     * @return File
     */
    public function create(string $filepath, int $userId): File
    {
        return new File(['filepath' => $filepath, 'user_id' => $userId]);
    }

    /**
     * @param mixed $value
     * @param string $field
     * @return File
     */
    public function get(mixed $value, string $field = "id"): File
    {
        return File::findOne([$field => $value]);
    }

    /**
     * @param int $userId
     * @return array|File[]
     */
    public function getFor(int $userId): array
    {
        return File::findAll(['user_id' => $userId]);
    }

    /**
     * @param File $file
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save(File $file): bool
    {
        return $file->save();
    }

    /**
     * @param File $file
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(File $file): bool
    {
        return $file->delete();
    }
}