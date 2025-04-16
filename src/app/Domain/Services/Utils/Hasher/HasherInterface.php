<?php

namespace App\Domain\Services\Utils\Hasher;

interface HasherInterface
{
    public function hash(string $value): string;
}
