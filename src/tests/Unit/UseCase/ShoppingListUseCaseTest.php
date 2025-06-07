<?php

namespace Tests\Unit\UseCase;

use App\Domain\Entities\Group;
use App\Domain\Entities\ShoppingList;
use App\Domain\Exceptions\GroupNotFoundException;
use App\Domain\Services\Auth\AuthorizeServiceInterface;
use App\Domain\Services\Broadcast\BroadcastServiceInterface;
use App\Domain\UseCase\Group\GroupRepositoryInterface;
use App\Domain\UseCase\ShoppingList\ShoppingListRepositoryInterface;
use App\Domain\UseCase\ShoppingList\ShoppingListUseCase;
use DateTime;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class ShoppingListUseCaseTest extends TestCase
{
    private ShoppingListRepositoryInterface $shoppingListRepository;
    private GroupRepositoryInterface $groupRepository;
    private BroadcastServiceInterface $broadcastService;
    private AuthorizeServiceInterface $authorizeService;
    private ShoppingListUseCase $useCase;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->shoppingListRepository = $this->createMock(ShoppingListRepositoryInterface::class);
        $this->groupRepository = $this->createMock(GroupRepositoryInterface::class);
        $this->broadcastService = $this->createMock(BroadcastServiceInterface::class);
        $this->authorizeService = $this->createMock(AuthorizeServiceInterface::class);

        $this->useCase = new ShoppingListUseCase(
            $this->shoppingListRepository,
            $this->groupRepository,
            $this->broadcastService,
            $this->authorizeService
        );
    }

    public function testFindAllReturnsArray()
    {
        $groupId = 1;
        $userId = 10;

        $shoppingList = new ShoppingList(
            id: 1,
            name: 'Test List',
            groupId: $groupId,
            createdAt: new DateTime(),
            updatedAt: new DateTime(),
            hidden: false,
            products: []
        );

        $this->authorizeService
            ->expects($this->once())
            ->method('authorizeGroupMember');

        $groupMock = $this->createMock(Group::class);
        $groupMock->id = $groupId;

        $this->groupRepository
            ->method('findById')
            ->willReturn($groupMock);

        $this->shoppingListRepository
            ->method('findAll')
            ->with($groupId)
            ->willReturn([$shoppingList]);

        $result = $this->useCase->findAll($userId, $groupId);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(ShoppingList::class, $result[0]);
    }

    /**
     * @throws GroupNotFoundException
     * @throws Exception
     */
    public function testCreateReturnsShoppingList()
    {
        $groupId = 2;
        $userId = 5;
        $name = 'Created List';
        $hidden = false;

        $shoppingList = new ShoppingList(
            id: 2,
            name: $name,
            groupId: $groupId,
            createdAt: new DateTime(),
            updatedAt: new DateTime(),
            hidden: $hidden,
            products: []
        );

        $this->authorizeService
            ->expects($this->once())
            ->method('authorizeGroupMember');

        $groupMock = $this->createMock(Group::class);
        $groupMock->id = $groupId;

        $this->groupRepository
            ->method('findById')
            ->willReturn($groupMock);

        $this->shoppingListRepository
            ->expects($this->once())
            ->method('create')
            ->with($name, $groupId, $hidden)
            ->willReturn($shoppingList);

        $this->broadcastService
            ->expects($this->once())
            ->method('broadcastListChanged');

        $result = $this->useCase->create($userId, $name, $groupId, $hidden);

        $this->assertInstanceOf(ShoppingList::class, $result);
        $this->assertEquals($name, $result->name);
    }

    public function testDeleteReturnsDestroyedList()
    {
        $shoppingListId = 3;

        $shoppingList = new ShoppingList(
            id: $shoppingListId,
            name: 'To Delete',
            groupId: 1,
            createdAt: new DateTime(),
            updatedAt: new DateTime(),
            hidden: false,
            products: []
        );

        $this->authorizeService
            ->expects($this->once())
            ->method('authorizeGroupMemberByShoppingList');

        $this->shoppingListRepository
            ->expects($this->once())
            ->method('destroy')
            ->with($shoppingListId)
            ->willReturn($shoppingList);

        $this->broadcastService
            ->expects($this->once())
            ->method('broadcastListChanged');

        $result = $this->useCase->delete($shoppingListId);

        $this->assertInstanceOf(ShoppingList::class, $result);
        $this->assertEquals($shoppingListId, $result->id);
    }
}
