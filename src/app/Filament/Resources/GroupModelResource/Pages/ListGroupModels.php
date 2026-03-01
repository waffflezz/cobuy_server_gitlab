<?php

namespace App\Filament\Resources\GroupModelResource\Pages;

use App\Filament\Resources\GroupModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGroupModels extends ListRecords
{
    protected static string $resource = GroupModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
