<?php

namespace App\Filament\Resources\UserModelResource\Pages;

use App\Filament\Resources\UserModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserModel extends EditRecord
{
    protected static string $resource = UserModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
