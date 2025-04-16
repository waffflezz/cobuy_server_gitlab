<?php

namespace App\Domain\UseCase\Auth;

use App\Domain\Entities\User;

interface UserRepositoryInterface
{
    public function create(string $name, string $email, string $password): User;
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function save(User $user): void;
}
