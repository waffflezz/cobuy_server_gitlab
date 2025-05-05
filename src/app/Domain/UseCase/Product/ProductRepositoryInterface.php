<?php

namespace App\Domain\UseCase\Product;

use App\Domain\Entities\Product;

interface ProductRepositoryInterface
{
    public function findAll(int $shoppingListId): array;

    public function create(array $data): Product;

    public function findById(int $productId): Product;

    public function update(int $productId, array $data): Product;

    public function destroy(int $productId): Product;

    public function uploadImage(int $productId, string $image): Product;

    public function destroyImage(int $productId): Product;
}
