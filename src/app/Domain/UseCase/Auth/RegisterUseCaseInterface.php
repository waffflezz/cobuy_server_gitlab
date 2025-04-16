<?php

namespace App\Domain\UseCase\Auth;

use App\Domain\Entities\User;

interface RegisterUseCaseInterface
{
    public function register(string $name, string $email, string $password): User;
    public function login(string $email, string $password): User;
    public function logout(): bool;
}
