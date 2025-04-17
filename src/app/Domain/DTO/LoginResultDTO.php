<?php

namespace App\Domain\DTO;

use App\Domain\Entities\User;

readonly class LoginResultDTO
{
    public function __construct(
        public User   $user,
        public string $token
    ) {}
}
