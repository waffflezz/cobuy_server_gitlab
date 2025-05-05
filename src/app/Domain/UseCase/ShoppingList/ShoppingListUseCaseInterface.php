<?php

namespace App\Domain\UseCase\ShoppingList;

use App\Domain\DTO\FileDTO;
use App\Domain\Entities\ShoppingList;

interface ShoppingListUseCaseInterface
{
    public function findAll(int $userId, int $groupId): array;

    public function create(int $userId, string $name, int $groupId, bool $hidden): ShoppingList;

    public function findById(int $shoppingListId): ShoppingList;

    public function update(int $shoppingListId, array $data): ShoppingList;

    public function delete(int $shoppingListId): ShoppingList;
}
