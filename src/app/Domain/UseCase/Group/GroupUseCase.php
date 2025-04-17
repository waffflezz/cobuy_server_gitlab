<?php

namespace App\Domain\UseCase\Group;

use App\Domain\DTO\FileDTO;
use App\Domain\Entities\Group;
use App\Domain\Services\File\FileStorageInterface;
use App\Domain\UseCase\Group\GroupUseCaseInterface;
use App\Domain\UseCase\User\UserRepositoryInterface;

class GroupUseCase implements GroupUseCaseInterface
{
    public function __construct(
        private readonly FileStorageInterface $fileStorage,
        private readonly GroupRepositoryInterface $groupRepository
    ) {}

    public function getAll(int $userId): array
    {
        return $this->groupRepository->findAll($userId);
    }

    public function create(int $userId, string $name, ?FileDTO $fileDTO): Group
    {
        $path = $this->fileStorage->storeFile($fileDTO, 'public/groups');

        return $this->groupRepository->create($userId, $name, $path);
    }
}
