<?php

namespace App\Filament\Resources\GroupModelResource\Pages;

use App\Filament\Resources\GroupModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGroupModel extends ViewRecord
{
    protected static string $resource = GroupModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
