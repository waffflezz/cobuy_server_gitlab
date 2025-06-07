<?php

namespace Tests\Unit\UseCase;

use App\Domain\DTO\FileDTO;
use App\Domain\DTO\Policy\ShoppingListIdDTO;
use App\Domain\Entities\Product;
use App\Domain\Entities\ShoppingList;
use App\Domain\Services\Auth\AuthorizeServiceInterface;
use App\Domain\Services\Broadcast\BroadcastServiceInterface;
use App\Domain\Services\File\FileStorageInterface;
use App\Domain\UseCase\Product\ProductRepositoryInterface;
use App\Domain\UseCase\Product\ProductUseCase;
use App\Domain\UseCase\ShoppingList\ShoppingListRepositoryInterface;
use App\Events\EventType;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class ProductUseCaseTest extends TestCase
{
    private ProductUseCase $useCase;
    private $productRepository;
    private $shoppingListRepository;
    private $broadcastService;
    private $fileStorage;
    private $authorizeService;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->shoppingListRepository = $this->createMock(ShoppingListRepositoryInterface::class);
        $this->broadcastService = $this->createMock(BroadcastServiceInterface::class);
        $this->fileStorage = $this->createMock(FileStorageInterface::class);
        $this->authorizeService = $this->createMock(AuthorizeServiceInterface::class);

        $this->useCase = new ProductUseCase(
            $this->productRepository,
            $this->shoppingListRepository,
            $this->broadcastService,
            $this->fileStorage,
            $this->authorizeService
        );
    }

    public function testFindAll(): void
    {
        $shoppingList = new ShoppingList(1, 'List', 1, new \DateTime(), new \DateTime(), false, []);

        $this->authorizeService->expects($this->once())
            ->method('authorizeGroupMemberByShoppingList')
            ->with(new ShoppingListIdDTO(1));

        $this->shoppingListRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($shoppingList);

        $this->assertSame([], $this->useCase->findAll(1));
    }

    /**
     * @throws Exception
     */
    public function testCreate(): void
    {
        $data = ['name' => 'Milk'];
        $product = $this->createMock(Product::class);
        $shoppingList = new ShoppingList(1, 'List', 1, new \DateTime(), new \DateTime(), false);

        $this->authorizeService->expects($this->once())
            ->method('authorizeGroupMemberByShoppingList');

        $this->productRepository->expects($this->once())
            ->method('create')
            ->willReturn($product);

        $this->shoppingListRepository->expects($this->once())
            ->method('findById')
            ->willReturn($shoppingList);

        $this->broadcastService->expects($this->once())
            ->method('broadcastProductChanged')
            ->with($product, EventType::Create);

        $this->broadcastService->expects($this->once())
            ->method('broadcastListChanged')
            ->with($shoppingList, EventType::Update);

        $result = $this->useCase->create(1, $data);

        $this->assertSame($product, $result);
    }

    /**
     * @throws Exception
     */
    public function testDelete(): void
    {
        $product = $this->createMock(Product::class);

        $this->authorizeService->expects($this->once())
            ->method('authorizeGroupMemberByShoppingList');

        $this->productRepository->expects($this->once())
            ->method('destroy')
            ->with(2)
            ->willReturn($product);

        $this->broadcastService->expects($this->once())
            ->method('broadcastProductChanged')
            ->with($product);

        $this->useCase->delete(1, 2);
    }
}
