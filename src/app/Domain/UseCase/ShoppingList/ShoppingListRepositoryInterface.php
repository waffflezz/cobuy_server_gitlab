<?php

namespace App\Domain\UseCase\ShoppingList;

use App\Domain\Entities\Group;
use App\Domain\Entities\ShoppingList;
use App\Domain\Exceptions\GroupNotFoundException;
use App\Http\Requests\Group\GroupUpdateRequest;

interface ShoppingListRepositoryInterface
{
    /**
     * @param int $groupId
     * @return ShoppingList[]
     */
    public function findAll(int $groupId): array;
    public function create(string $name, int $groupId, bool $hidden): ShoppingList;

    public function findById(int $id): ?ShoppingList;

    public function update(int $id, array $data): ShoppingList;

    public function destroy(int $id): ShoppingList;
}
