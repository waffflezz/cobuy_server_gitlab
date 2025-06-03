<?php

namespace App\Domain\UseCase\Group;

use App\Domain\DTO\FileDTO;
use App\Domain\DTO\ImageResultDTO;
use App\Domain\Entities\Group;

interface GroupUseCaseInterface
{
    /**
     * @param int $userId
     * @return Group[]
     */
    public function findAll(int $userId): array;
    public function create(int $userId, string $name, ?FileDTO $fileDTO): Group;
    public function findById(int $userId, int $groupId): Group;
    public function update(int $groupId, array $data, ?FileDTO $fileDTO): Group;
    public function delete(int $groupId): Group;
    public function leave(int $userId, int $groupId): Group;
    public function kick(int $userId, int $groupId): Group;
    public function uploadImage(int $groupId, ?FileDTO $fileDTO): Group;
    public function showImage(int $groupId): ImageResultDTO;
    public function destroyImage(int $groupId): Group;
    public function getInviteLink(int $userId, int $groupId): string;
    public function invite(int $userId, string $token): Group;
}
