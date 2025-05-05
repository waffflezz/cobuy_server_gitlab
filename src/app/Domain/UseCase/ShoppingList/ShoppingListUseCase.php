<?php

namespace App\Domain\UseCase\ShoppingList;

use App\Domain\DTO\Policy\GroupIdDTO;
use App\Domain\DTO\Policy\ShoppingListIdDTO;
use App\Domain\Entities\ShoppingList;
use App\Domain\Exceptions\GroupNotFoundException;
use App\Domain\Services\Auth\AuthorizeServiceInterface;
use App\Domain\Services\Broadcast\BroadcastServiceInterface;
use App\Domain\UseCase\Group\GroupRepositoryInterface;
use App\Events\EventType;

class ShoppingListUseCase implements ShoppingListUseCaseInterface
{
    public function __construct(
        private readonly ShoppingListRepositoryInterface $shoppingListRepository,
        private readonly GroupRepositoryInterface $groupRepository,
        private readonly BroadcastServiceInterface $broadcastService,
        private readonly AuthorizeServiceInterface $authorizeService
    ) {}

    /**
     * @throws GroupNotFoundException
     */
    public function findAll(int $userId, int $groupId): array
    {
        $this->authorizeService->authorizeGroupMember(new GroupIdDTO($groupId));

        $group = $this->groupRepository->findById($groupId);
        return $this->shoppingListRepository->findAll($group->id);
    }

    /**
     * @throws GroupNotFoundException
     */
    public function create(int $userId, string $name, int $groupId, bool $hidden): ShoppingList
    {
        $this->authorizeService->authorizeGroupMember(new GroupIdDTO($groupId));

        $group = $this->groupRepository->findById($groupId);
        $shoppingList = $this->shoppingListRepository->create($name, $group->id, $hidden);

        $this->broadcastService->broadcastListChanged($shoppingList, EventType::Create);

        return $shoppingList;
    }

    public function findById(int $shoppingListId): ShoppingList
    {
        $this->authorizeService->authorizeGroupMemberByShoppingList(new ShoppingListIdDTO($shoppingListId));

        return $this->shoppingListRepository->findById($shoppingListId);
    }

    public function update(int $shoppingListId, array $data): ShoppingList
    {
        $this->authorizeService->authorizeGroupMemberByShoppingList(new ShoppingListIdDTO($shoppingListId));

        $shoppingListUpdated = $this->shoppingListRepository->update($shoppingListId, $data);

        $this->broadcastService->broadcastListChanged($shoppingListUpdated, EventType::Update);

        return $shoppingListUpdated;
    }

    public function delete(int $shoppingListId): ShoppingList
    {
        $this->authorizeService->authorizeGroupMemberByShoppingList(new ShoppingListIdDTO($shoppingListId));

        $shoppingListDestroyed = $this->shoppingListRepository->destroy($shoppingListId);

        $this->broadcastService->broadcastListChanged($shoppingListDestroyed, EventType::Delete);

        return $shoppingListDestroyed;
    }
}
