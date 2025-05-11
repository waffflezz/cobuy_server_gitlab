<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entities\User;
use App\Domain\Exceptions\InvalidTokenException;
use App\Domain\Exceptions\UserNotFoundException;
use App\Domain\UseCase\User\UserRepositoryInterface;
use App\Models\UserModel;
use Laravel\Sanctum\PersonalAccessToken;

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
        $user = UserModel::find($id);
        return $user ? $user->toDomain() : null;
    }

    public function findByEmail(string $email): ?User
    {
        $user = UserModel::where('email', $email)->first();
        return $user?->toDomain();
    }

    /**
     * @throws UserNotFoundException
     */
    public function update(User $user): void
    {
       $updateUser = UserModel::find($user->id);

       if (!$updateUser) {
            throw new UserNotFoundException("User with id {$user->id} was not found", 404);
       }

       $updateUser->fill($user->toArray());
       $updateUser->save();
    }

    /**
     * @throws UserNotFoundException
     */
    public function createToken(int $userid): string
    {
        $user = UserModel::find($userid);
        if (!$user) {
            throw new UserNotFoundException("User with id {$userid} was not found", 404);
        }

        return $user->createToken('auth_token')->plainTextToken;
    }

    /**
     * @throws UserNotFoundException
     */
    public function deleteToken(int $userId): void
    {
        $user = UserModel::find($userId);
        if (!$user) {
            throw new UserNotFoundException("User with id $userId was not found", 404);
        }

        $user->tokens()->delete();
    }

    public function findByToken(string $token): ?User
    {
        $tokenModel = PersonalAccessToken::findToken($token);
        if (!$tokenModel) {
            throw new InvalidTokenException('Invalid or expired token', 404);
        }

        $user = $tokenModel->tokenable;

        if (!$user) {
            throw new UserNotFoundException("User not found", 404);
        }

        return $user->toDomain();
    }
}
