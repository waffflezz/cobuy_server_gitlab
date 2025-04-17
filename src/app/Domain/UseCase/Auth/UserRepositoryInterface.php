<?php

namespace App\Domain\UseCase\Auth;

use App\Domain\Entities\User;

interface UserRepositoryInterface
{
    public function create(string $name, string $email, string $password): User;
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function findByToken(string $token): ?User;
    public function update(User $user): void;
    public function createToken(int $userid): string;
    public function deleteToken(int $userId): void;
}
