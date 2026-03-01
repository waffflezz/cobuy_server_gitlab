<?php

namespace App\Filament\Resources\ShoppingListModelResource\Pages;

use App\Domain\UseCase\ShoppingList\ShoppingListUseCaseInterface;
use App\Filament\Resources\ShoppingListModelResource;
use App\Models\ShoppingListModel;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateShoppingListModel extends CreateRecord
{
    protected static string $resource = ShoppingListModelResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $shoppingListUseCase = app(ShoppingListUseCaseInterface::class);

        $groupId = $data['group_id'];

        $domainShoppingList = $shoppingListUseCase->create(
            Auth::id(),
            $data["name"],
            $groupId,
            $data["hidden"],
        );

        return ShoppingListModel::query()->findOrFail($domainShoppingList->id);
    }
}
