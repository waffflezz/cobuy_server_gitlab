<?php

namespace App\Domain\UseCase\Group;

use App\Domain\Entities\Group;
use App\Domain\Exceptions\GroupNotFoundException;
use App\Http\Requests\Group\GroupUpdateRequest;

interface GroupRepositoryInterface
{
    /**
     * @return Group[]
     */
    public function findAll(int $userId): array;

    /**
     * @param int $groupId
     * @return Group
     * @throws GroupNotFoundException
     */
    public function findById(int $groupId): Group;
    public function create(int $userId, string $name, ?string $image): Group;
    public function update(int $groupId, array $data): Group;
    public function delete(int $groupId): Group;
    public function leave(int $userId, int $groupId): Group;
    public function kick(int $userId, int $groupId): Group;
    public function uploadImage(int $groupId, string $image): Group;
    public function destroyImage(int $groupId): Group;
    public function showImageUrl(int $groupId): string;
    public function updateInviteLink(int $groupId, string $inviteLink): Group;
    public function showInviteLink(int $groupId): ?string;
    public function invite(int $userId, int $groupId): Group;
}
