<?php

namespace App\Filament\Resources\GroupModelResource\Pages;

use App\Domain\DTO\FileDTO;
use App\Domain\UseCase\Group\GroupUseCaseInterface;
use App\Filament\Resources\GroupModelResource;
use App\Models\GroupModel;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CreateGroupModel extends CreateRecord
{
    protected static string $resource = GroupModelResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $groupUseCase = app(GroupUseCaseInterface::class);

        $fileDTO = null;
        $imagePath = $data['image'] ?? null;
        if (is_string($imagePath) && $imagePath !== '') {
            $absPath = Storage::disk('local')->path($imagePath);
            $fileDTO = new FileDTO(
                originalName: basename($imagePath),
                extension: pathinfo($imagePath, PATHINFO_EXTENSION),
                temporaryPath: $absPath,
            );
        }

        $domainGroup = $groupUseCase->create(
            $data["owner_id"],
            $data["name"],
            $fileDTO,
        );

        $groupId = $domainGroup->id;
        return GroupModel::query()->findOrFail($groupId);
    }
}
