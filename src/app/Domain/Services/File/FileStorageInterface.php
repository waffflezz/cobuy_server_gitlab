<?php

namespace App\Domain\Services\File;

use App\Domain\DTO\FileDTO;

interface FileStorageInterface
{
    public function storeFile(FileDTO $file, string $directory): string;
}
