<?php

namespace App\Filament\Resources\UserModelResource\Pages;

use App\Filament\Resources\UserModelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserModel extends CreateRecord
{
    protected static string $resource = UserModelResource::class;
}
