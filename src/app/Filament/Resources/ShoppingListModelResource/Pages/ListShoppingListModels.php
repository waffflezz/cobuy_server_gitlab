<?php

namespace App\Filament\Resources\ShoppingListModelResource\Pages;

use App\Filament\Resources\ShoppingListModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShoppingListModels extends ListRecords
{
    protected static string $resource = ShoppingListModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
