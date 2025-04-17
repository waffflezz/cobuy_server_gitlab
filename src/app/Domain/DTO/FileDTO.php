<?php

namespace App\Domain\DTO;

readonly class FileDTO
{
    public function __construct(
        public string $originalName,
        public string $extension,
        public string $temporaryPath
    ) {}
}
