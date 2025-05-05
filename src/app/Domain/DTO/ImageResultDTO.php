<?php

namespace App\Domain\DTO;

class ImageResultDTO
{
    public function __construct(
        public readonly string $image,
    ) {}
}
