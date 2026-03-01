<?php

namespace App\Domain\UseCase\Product;

use App\Domain\DTO\FileDTO;
use App\Domain\DTO\Policy\GroupIdDTO;
use App\Domain\DTO\Policy\ShoppingListIdDTO;
use App\Domain\Entities\Product;
use App\Domain\Services\Auth\AuthorizeServiceInterface;
use App\Domain\Services\Broadcast\BroadcastServiceInterface;
use App\Domain\Services\File\FileStorageInterface;
use App\Domain\UseCase\ShoppingList\ShoppingListRepositoryInterface;
use App\Events\EventType;

class ProductUseCase implements ProductUseCaseInterface
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ShoppingListRepositoryInterface $shoppingListRepository,
        private readonly BroadcastServiceInterface $broadcastService,
        private readonly FileStorageInterface $fileStorage,
        private readonly AuthorizeServiceInterface $authorizeService
    ) {}

    public function findAll(int $shoppingListId): array
    {
        $this->authorizeService->authorizeGroupMemberByShoppingList(new ShoppingListIdDTO($shoppingListId));
        $shoppingList = $this->shoppingListRepository->findById($shoppingListId);

        return $shoppingList->products;
    }

    public function create(int $userId, int $shoppingListId, array $data, ?FileDTO $fileDTO): Product
    {
        $this->authorizeService->authorizeGroupMemberByShoppingList(new ShoppingListIdDTO($shoppingListId));

        $data['shopping_list_id'] = $shoppingListId;
        $data['status'] = Product::NONE_STATUS;


        $path = null;
        if ($fileDTO) {
            $path = $this->fileStorage->storeFile($fileDTO, 'products');
        }

        $product = $this->productRepository->create($data, $path);
        $shoppingList = $this->shoppingListRepository->findById($shoppingListId);

        $this->broadcastService->broadcastProductChanged($product, EventType::Create);
        $this->broadcastService->broadcastListChanged($shoppingList, EventType::Update);

        return $product;
    }

    public function findById(int $shoppingListId, int $productId): Product
    {
        $this->authorizeService->authorizeGroupMemberByShoppingList(new ShoppingListIdDTO($shoppingListId));

        return $this->productRepository->findById($productId);
    }

    public function update(int $userId, int $shoppingListId, int $productId, array $data, ?FileDTO $fileDTO): Product
    {
        $this->authorizeService->authorizeGroupMemberByShoppingList(new ShoppingListIdDTO($shoppingListId));

        if ($fileDTO) {
            $path = $this->fileStorage->storeFile($fileDTO, 'products');
            $data['image'] = $path;
        }

        if (isset($data['status'])) {
            if ((int) $data['status'] !== Product::NONE_STATUS) {
                $data['buyer_id'] = $userId;
            } else {
                $data['buyer_id'] = null;
            }
        }

        $product = $this->productRepository->update($productId, $data);
        $shoppingList = $this->shoppingListRepository->findById($shoppingListId);

        $this->broadcastService->broadcastListChanged($shoppingList, EventType::Update);
        $this->broadcastService->broadcastProductChanged($product, EventType::Update);

        return $product;
    }

    public function delete(int $shoppingListId, int $productId): void
    {
        $this->authorizeService->authorizeGroupMemberByShoppingList(new ShoppingListIdDTO($shoppingListId));

        $product = $this->productRepository->destroy($productId);

        $this->broadcastService->broadcastProductChanged($product, EventType::Delete);
    }

    public function uploadImage(int $shoppingListId, int $productId, FileDTO $fileDTO): Product
    {
        $this->authorizeService->authorizeGroupMemberByShoppingList(new ShoppingListIdDTO($shoppingListId));

        $path = $this->fileStorage->storeFile($fileDTO, 'products');

        $product = $this->productRepository->uploadImage($productId, $path);

        $this->broadcastService->broadcastProductChanged($product, EventType::Update);

        return $product;
    }

    public function destroyImage(int $shoppingListId, int $productId): Product
    {
        $this->authorizeService->authorizeGroupMemberByShoppingList(new ShoppingListIdDTO($shoppingListId));

        $product = $this->productRepository->destroyImage($productId);

        $this->broadcastService->broadcastProductChanged($product, EventType::Update);

        return $product;
    }
}
