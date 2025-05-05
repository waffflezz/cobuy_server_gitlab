<?php

namespace App\Infrastructure\File;

use App\Domain\DTO\FileDTO;
use App\Domain\Services\File\FileStorageInterface;
use Illuminate\Support\Facades\Storage;

class LaravelFileStorage implements FileStorageInterface
{
    public function storeFile(FileDTO $file, string $directory): string
    {
        $path = $file->temporaryPath;
        $extension = $file->extension;
        return Storage::disk('public')->putFileAs($directory, $path, time() . '.' . $extension);
    }
}
