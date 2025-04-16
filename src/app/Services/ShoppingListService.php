<?php

namespace App\Services;

use App\Models\ShoppingList;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShoppingListService
{
    public function getShoppingList(UserModel $user, string $shoppingListId)
    {
        $shoppingList = ShoppingList::whereIn('group_id', $user->groups->pluck('id'))->find($shoppingListId);
        if (!$shoppingList) {
            throw new ModelNotFoundException('ShoppingList by ID: ' . $shoppingListId . ' not found');
        }

        return $shoppingList;
    }
}
