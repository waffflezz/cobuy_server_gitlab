<?php

namespace App\Domain\UseCase\Auth;

use App\Domain\DTO\LoginResultDTO;
use App\Domain\Entities\User;
use App\Domain\Exceptions\InvalidCredentialsException;
use App\Domain\Exceptions\UserNotFoundException;
use App\Domain\Services\Utils\Hasher\HasherInterface;
use App\Domain\UseCase\User\UserRepositoryInterface;

class RegisterUseCase implements RegisterUseCaseInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly HasherInterface         $hasher,
    ) {}

    public function register(string $name, string $email, string $password): User
    {
        $hashPassword = $this->hasher->hash($password);
        return $this->userRepository->create($name, $email, $hashPassword);
    }

    /**
     * @throws InvalidCredentialsException
     * @throws UserNotFoundException
     */
    public function login(string $email, string $password): LoginResultDTO
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new UserNotFoundException("Invalid email", 401);
        }

        if (!$this->hasher->check($password, $user->password)) {
            throw new InvalidCredentialsException("Invalid credentials", 401);
        }

        $token = $this->userRepository->createToken($user->id);

        return new LoginResultDTO($user, $token);
    }

    public function logout(int $userId): void
    {
        $user = $this->userRepository->findById($userId);
        $this->userRepository->deleteToken($user->id);
    }
}
