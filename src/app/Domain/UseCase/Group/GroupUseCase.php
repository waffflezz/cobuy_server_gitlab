<?php

namespace App\Domain\UseCase\Group;

use App\Domain\DTO\FileDTO;
use App\Domain\DTO\ImageResultDTO;
use App\Domain\DTO\Policy\GroupIdDTO;
use App\Domain\Entities\Group;
use App\Domain\Exceptions\GroupNotFoundException;
use App\Domain\Services\Auth\AuthorizeServiceInterface;
use App\Domain\Services\Broadcast\BroadcastServiceInterface;
use App\Domain\Services\File\FileStorageInterface;
use App\Domain\Services\Jwt\JwtServiceInterface;
use App\Events\EventType;

class GroupUseCase implements GroupUseCaseInterface
{
    public function __construct(
        private readonly FileStorageInterface $fileStorage,
        private readonly GroupRepositoryInterface $groupRepository,
        private readonly BroadcastServiceInterface $broadcastService,
        private readonly AuthorizeServiceInterface $authorizeService,
        private readonly JwtServiceInterface $jwtService,
    ) {}

    public function findAll(int $userId): array
    {
        return $this->groupRepository->findAll($userId);
    }

    public function create(int $userId, string $name, ?FileDTO $fileDTO): Group
    {
        $path = null;
        if ($fileDTO) {
            $path = $this->fileStorage->storeFile($fileDTO, 'public/groups');
        }

        $group = $this->groupRepository->create($userId, $name, $path);

        $this->broadcastService->broadcastGroupChanged($group, EventType::Create);

        return $group;
    }

    /**
     * @throws GroupNotFoundException
     */
    public function findById(int $userId, int $groupId): Group
    {
        $this->authorizeService->authorizeGroupMember(new GroupIdDTO($groupId));
        return $this->groupRepository->findByUserAndGroupId($userId, $groupId);
    }

    public function update(int $groupId, array $data, ?FileDTO $fileDTO): Group
    {
        $this->authorizeService->authorizeGroupOwner(new GroupIdDTO($groupId));

        if ($fileDTO) {
            $path = $this->fileStorage->storeFile($fileDTO, 'public/groups');
            $data['image'] = $path;
        }

        $group = $this->groupRepository->update($groupId, $data);

        $this->broadcastService->broadcastGroupChanged($group, EventType::Update);

        return $group;
    }

    public function delete(int $groupId): Group
    {
        $this->authorizeService->authorizeGroupOwner(new GroupIdDTO($groupId));

        $group = $this->groupRepository->delete($groupId);

        $this->broadcastService->broadcastGroupChanged($group, EventType::Delete);

        return $group;
    }

    public function leave(int $userId, int $groupId): Group
    {
        $group = $this->groupRepository->leave($userId, $groupId);

        $this->broadcastService->broadcastGroupChanged($group, EventType::Update);

        return $group;
    }

    public function kick(int $userId, int $groupId): Group
    {
        $this->authorizeService->authorizeGroupOwner(new GroupIdDTO($groupId));

        $group = $this->groupRepository->kick($userId, $groupId);

        $this->broadcastService->broadcastGroupChanged($group, EventType::Update);

        return $group;
    }

    public function uploadImage(int $groupId, FileDTO $fileDTO): Group
    {
        $this->authorizeService->authorizeGroupOwner(new GroupIdDTO($groupId));

        $path = $this->fileStorage->storeFile($fileDTO, 'public/groups');
        $group = $this->groupRepository->uploadImage($groupId, $path);
        $this->broadcastService->broadcastGroupChanged($group, EventType::Update);

        return $group;
    }

    public function showImage(int $groupId): ImageResultDTO
    {
        $this->authorizeService->authorizeGroupMember(new GroupIdDTO($groupId));

        $image = $this->groupRepository->showImageUrl($groupId);

        return new ImageResultDTO($image);
    }

    public function destroyImage(int $groupId): Group
    {
        $this->authorizeService->authorizeGroupOwner(new GroupIdDTO($groupId));

        $group = $this->groupRepository->destroyImage($groupId);
        $this->broadcastService->broadcastGroupChanged($group, EventType::Delete);

        return $group;
    }

    /**
     * @throws GroupNotFoundException
     */
    public function getInviteLink(int $userId, int $groupId): string
    {
        $group = $this->groupRepository->findByUserAndGroupId($userId, $groupId);
        $this->authorizeService->authorizeGroupMember(new GroupIdDTO($groupId));

        if (is_null($group->inviteLink) || $this->jwtService->isValid($group->inviteLink, 'inviteToken')) {
            $token = $this->jwtService->getToken('inviteToken', [
                'groupId' => $groupId,
            ]);
            $this->groupRepository->updateInviteLink($groupId, $token);
            return $token;
        }

        return $group->inviteLink;
    }

    public function invite(int $userId, string $token): Group
    {
        $groupId = $this->jwtService->getValidatePayload($token, 'inviteToken', 'groupId');

        return $this->groupRepository->invite($userId, $groupId);
    }
}
