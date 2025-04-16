<?php

namespace App\Infrastructure\Repository\User;

use App\Domain\Entities\User;
use App\Domain\UseCase\Auth\UserRepositoryInterface;
use App\Models\UserModel;

class UserRepository implements UserRepositoryInterface
{
    public function create(string $name, string $email, string $password): User
    {
        return UserModel::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ])->toDomain();
    }

    public function findById(int $id): ?User
    {

    }

    public function findByEmail(string $email): ?User
    {
        // TODO: Implement findByEmail() method.
    }

    public function save(User $user): void
    {
        // TODO: Implement save() method.
    }
}
