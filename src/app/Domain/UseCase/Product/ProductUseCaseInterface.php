<?php

namespace App\Domain\UseCase\Product;

use App\Domain\DTO\FileDTO;
use App\Domain\Entities\Product;

interface ProductUseCaseInterface
{
    public function findAll(int $shoppingListId): array;

    public function create(int $userId, int $shoppingListId, array $data, ?FileDTO $fileDTO): Product;

    public function findById(int $shoppingListId, int $productId): Product;

    public function update(int $userId, int $shoppingListId, int $productId, array $data, ?FileDTO $fileDTO): Product;

    public function delete(int $shoppingListId, int $productId): void;

    public function uploadImage(int $shoppingListId, int $productId, FileDTO $fileDTO): Product;

    public function destroyImage(int $shoppingListId, int $productId): Product;
}
