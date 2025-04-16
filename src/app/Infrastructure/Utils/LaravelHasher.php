<?php

namespace App\Infrastructure\Utils;

use App\Domain\Services\Utils\Hasher\HasherInterface;
use Illuminate\Support\Facades\Hash;

class LaravelHasher implements HasherInterface
{

    public function hash(string $value): string
    {
        return Hash::make($value);
    }
}
