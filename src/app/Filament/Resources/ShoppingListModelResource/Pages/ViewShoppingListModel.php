<?php

namespace App\Filament\Resources\ShoppingListModelResource\Pages;

use App\Filament\Resources\ShoppingListModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewShoppingListModel extends ViewRecord
{
    protected static string $resource = ShoppingListModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
