<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entities\Group;
use App\Domain\Exceptions\GroupNotFoundException;
use App\Domain\Exceptions\UserAlreadyInvitedException;
use App\Domain\Exceptions\UserNotFoundException;
use App\Domain\UseCase\Group\GroupRepositoryInterface;
use App\Models\GroupModel;

class GroupRepository implements GroupRepositoryInterface
{
    /**
     * @inheritDoc
     * @throws UserNotFoundException
     */
    public function findAll(int $userId): array
    {
        /** @var GroupModel $group */
        $groups = GroupModel::whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->latest()->get();

        return $groups->map(fn(GroupModel $groupModel) => $groupModel->toDomain())->all();
    }

    public function create(int $userId, string $name, ?string $image): Group
    {
        /** @var GroupModel $group */
        $group = GroupModel::create([
            'owner_id' => $userId,
            'name' => $name,
            'image' => $image,
        ]);
        $group->users()->attach($userId);
        return $group->toDomain();
    }

    /**
     * @throws GroupNotFoundException
     */
    public function findById(int $groupId): Group
    {
        /** @var GroupModel $group */
        $group = GroupModel::find($groupId);

        if (!$group) {
            throw new GroupNotFoundException('Group with id ' . $groupId . ' not found', 404);
        }

        return $group->toDomain();
    }

    /**
     * @throws GroupNotFoundException
     */
    public function update(int $groupId, array $data): Group
    {
        /** @var GroupModel $group */
        $updatedGroup = GroupModel::find($groupId);

        if (!$updatedGroup) {
            throw new GroupNotFoundException('Group with id ' . $groupId . ' not found', 404);
        }

        $updatedGroup->fill($data);
        $updatedGroup->save();
        return $updatedGroup->toDomain();
    }

    /**
     * @throws GroupNotFoundException
     */
    public function delete(int $groupId): Group
    {
        /** @var GroupModel $group */
        $group = GroupModel::find($groupId);

        if (!$group) {
            throw new GroupNotFoundException('Group with ID ' . $groupId . ' not found', 404);
        }

        $group->delete();

        return $group->toDomain();
    }

    public function leave(int $userId, int $groupId): Group
    {
        /** @var GroupModel $group */
        $group = GroupModel::find($groupId);

        $group->users()->detach($userId);
        return $group->toDomain();
    }

    /**
     * @throws UserNotFoundException
     */
    public function kick(int $userId, int $groupId): Group
    {
        /** @var GroupModel $group */
        $group = GroupModel::find($groupId);
        if (!$group->users()->where('users.id', $userId)->exists()) {
            throw new UserNotFoundException('User with id ' . $userId . ' not found', 404);
        }

        $group->users()->detach($userId);
        return $group->toDomain();
    }

    /**
     * @throws GroupNotFoundException
     */
    public function uploadImage(int $groupId, string $image): Group
    {
        /** @var GroupModel $group */
        $group = GroupModel::find($groupId);

        if (!$group) {
            throw new GroupNotFoundException('Group with id ' . $groupId . ' not found', 404);
        }

        $group->image = $image;
        $group->save();

        return $group->toDomain();
    }

    /**
     * @throws GroupNotFoundException
     */
    public function destroyImage(int $groupId): Group
    {
        /** @var GroupModel $group */
        $group = GroupModel::find($groupId);
        if (!$group) {
            throw new GroupNotFoundException('Group with id ' . $groupId . ' not found', 404);
        }

        $group->image = null;
        $group->save();
        return $group->toDomain();
    }

    /**
     * @throws GroupNotFoundException
     */
    public function showImageUrl(int $groupId): string
    {
        /** @var GroupModel $group */
        $group = GroupModel::find($groupId);
        if (!$group) {
            throw new GroupNotFoundException('Group with id ' . $groupId . ' not found', 404);
        }
        return $group->image;
    }

    /**
     * @throws GroupNotFoundException
     */
    public function updateInviteLink(int $groupId, string $inviteLink): Group
    {
        $group = GroupModel::find($groupId);

        if (!$group) {
            throw new GroupNotFoundException('Group with id ' . $groupId . ' not found', 404);
        }

        $group->invite_link = $inviteLink;
        $group->save();

        return $group->toDomain();
    }

    /**
     * @throws GroupNotFoundException
     */
    public function showInviteLink(int $groupId): ?string
    {
        $group = GroupModel::find($groupId);

        if (!$group) {
            throw new GroupNotFoundException('Group with id ' . $groupId . ' not found', 404);
        }

        return $group->inviteLink;
    }

    /**
     * @throws GroupNotFoundException
     * @throws UserAlreadyInvitedException
     */
    public function invite(int $userId, int $groupId): Group
    {
        /** @var GroupModel $group */
        $group = GroupModel::find($groupId);

        if (!$group) {
            throw new GroupNotFoundException('Group with id ' . $groupId . ' not found', 404);
        }

        if ($group->users()->where('users.id', $userId)->exists()) {
            throw new UserAlreadyInvitedException('User with id ' . $userId . ' already invited', 409);
        }

        $group->users()->syncWithoutDetaching([$userId]);
        return $group->toDomain();
    }
}
