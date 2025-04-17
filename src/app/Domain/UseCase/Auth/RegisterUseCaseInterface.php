<?php

namespace App\Domain\UseCase\Auth;

use App\Domain\DTO\LoginResultDTO;
use App\Domain\Entities\User;
use App\Domain\Exceptions\InvalidCredentialsException;
use App\Domain\Exceptions\UserNotFoundException;

interface RegisterUseCaseInterface
{
    public function register(string $name, string $email, string $password): User;

    /**
     * @param string $email
     * @param string $password
     * @return LoginResultDTO
     * @throws InvalidCredentialsException
     * @throws UserNotFoundException
     */
    public function login(string $email, string $password): LoginResultDTO;
    public function logout(int $userId): void;
}
