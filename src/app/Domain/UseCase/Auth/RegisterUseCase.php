<?php

namespace App\Domain\UseCase\Auth;

use App\Domain\Entities\User;
use App\Domain\Services\Utils\Hasher\HasherInterface;

class RegisterUseCase implements RegisterUseCaseInterface
{
    public function __construct(
        private readonly userRepositoryInterface $userRepository,
        private readonly HasherInterface         $hasher,
    ) {}

    public function register(string $name, string $email, string $password): User
    {
        $hashPassword = $this->hasher->hash($password);
        return $this->userRepository->create($name, $email, $hashPassword);
    }

    public function login(string $email, string $password): User
    {
        // TODO: Implement login() method.
    }

    public function logout(): bool
    {
        // TODO: Implement logout() method.
    }
}
