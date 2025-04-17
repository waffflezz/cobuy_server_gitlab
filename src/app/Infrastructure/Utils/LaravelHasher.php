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

    public function check(string $value, string $hashedValue): bool
    {
        return Hash::check($value, $hashedValue);
    }
}
