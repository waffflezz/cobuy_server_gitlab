<?php

namespace App\Domain\UseCase\Auth;

use App\Domain\Entities\User;

readonly class LoginResultDTO
{
    public function __construct(
        public User   $user,
        public string $token
    ) {}
}
