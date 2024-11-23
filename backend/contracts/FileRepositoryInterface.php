<?php

namespace backend\contracts;

use backend\models\File;

interface FileRepositoryInterface
{
    /**
     * @param string $filepath
     * @param int $userId
     * @return File
     */
    public function create(string $filepath, int $userId): File;

    /**
     * @param mixed $value
     * @param string $field
     * @return File
     */
    public function get(mixed $value, string $field = "id"): File;

    /**
     * @param int $userId
     * @return array|File[]
     */
    public function getFor(int $userId): array;

    /**
     * @param File $file
     * @return bool
     */
    public function save(File $file): bool;

    /**
     * @param File $file
     * @return bool
     */
    public function delete(File $file): bool;
}