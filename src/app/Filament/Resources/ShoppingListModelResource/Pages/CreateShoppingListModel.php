<?php

namespace App\Filament\Resources\ShoppingListModelResource\Pages;

use App\Filament\Resources\ShoppingListModelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateShoppingListModel extends CreateRecord
{
    protected static string $resource = ShoppingListModelResource::class;
}
