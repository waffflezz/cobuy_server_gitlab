<?php

namespace App\Filament\Resources\ShoppingListModelResource\Pages;

use App\Filament\Resources\ShoppingListModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShoppingListModel extends EditRecord
{
    protected static string $resource = ShoppingListModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
