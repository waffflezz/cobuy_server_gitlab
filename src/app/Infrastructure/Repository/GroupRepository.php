<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entities\Group;
use App\Domain\Exceptions\UserNotFoundException;
use App\Domain\UseCase\Group\GroupRepositoryInterface;
use App\Models\GroupModel;
use App\Models\UserModel;

class GroupRepository implements GroupRepositoryInterface
{

    /**
     * @inheritDoc
     * @throws UserNotFoundException
     */
    public function findAll(int $userId): array
    {
        $groups = GroupModel::whereHas('users', function ($query) use ($userId) {
            $query->where('owner_id', $userId);
        })->latest()->get();

        return $groups->map(fn(GroupModel $groupModel) => $groupModel->toDomain())->all();
    }

    public function create(int $userId, string $name, ?string $image): Group
    {
        $group = GroupModel::create([
            'owner_id' => $userId,
            'name' => $name,
            'image' => $image,
        ]);
        $group->users()->attach($userId);
        return $group;
    }
}
