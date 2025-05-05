<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entities\ShoppingList;
use App\Domain\Exceptions\ShoppingListNotFoundException;
use App\Domain\UseCase\ShoppingList\ShoppingListRepositoryInterface;
use App\Models\ShoppingListModel;

class ShoppingListRepository implements ShoppingListRepositoryInterface
{
    public function findAll(int $groupId): array
    {
        $shoppingLists = ShoppingListModel::where('group_id', $groupId)->latest()->get();

        return $shoppingLists->map(fn(ShoppingListModel $shoppingListModel) => $shoppingListModel->toDomain())->all();
    }

    public function create(string $name, int $groupId, bool $hidden): ShoppingList
    {
        /** @var ShoppingListModel $shoppingList */
        $shoppingList = ShoppingListModel::create([
            'name' => $name,
            'group_id' => $groupId,
            'hidden' => $hidden,
        ]);

        return $shoppingList->toDomain();
    }

    /**
     * @throws ShoppingListNotFoundException
     */
    public function findById(int $id): ?ShoppingList
    {
        /** @var ShoppingListModel $shoppingListModel */
        $shoppingListModel = ShoppingListModel::find($id);

        if (!$shoppingListModel) {
            throw new ShoppingListNotFoundException('Shopping list with id ' . $id . ' was not found.');
        }

        return $shoppingListModel->toDomain();
    }

    /**
     * @throws ShoppingListNotFoundException
     */
    public function update(int $id, array $data): ShoppingList
    {
        /** @var ShoppingListModel $shoppingList */
        $shoppingList = ShoppingListModel::find($id);

        if (!$shoppingList) {
            throw new ShoppingListNotFoundException('Shopping list with id ' . $id . ' was not found.');
        }

        $shoppingList->update($data);
        return $shoppingList->toDomain();
    }

    /**
     * @throws ShoppingListNotFoundException
     */
    public function destroy(int $id): ShoppingList
    {
        /** @var ShoppingListModel $shoppingList */
        $shoppingList = ShoppingListModel::find($id);

        if (!$shoppingList) {
            throw new ShoppingListNotFoundException('Shopping list with id ' . $id . ' was not found.');
        }

        $shoppingList->delete();
        return $shoppingList->toDomain();
    }
}
