<?php

namespace App\Filament\Resources\UserModelResource\Pages;

use App\Filament\Resources\UserModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUserModel extends ViewRecord
{
    protected static string $resource = UserModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
