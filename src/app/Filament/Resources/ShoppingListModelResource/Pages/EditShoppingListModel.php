<?php

namespace App\Filament\Resources\ShoppingListModelResource\Pages;

use App\Domain\UseCase\ShoppingList\ShoppingListUseCaseInterface;
use App\Filament\Resources\ShoppingListModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditShoppingListModel extends EditRecord
{
    protected static string $resource = ShoppingListModelResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $shoppingListUseCase = app(ShoppingListUseCaseInterface::class);

        $shoppingListUseCase->update(
            (int) $record->getKey(),
            $data,
        );

        return $record->fresh() ?? $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
