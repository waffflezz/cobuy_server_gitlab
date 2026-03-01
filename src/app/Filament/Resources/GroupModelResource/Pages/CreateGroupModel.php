<?php

namespace App\Filament\Resources\GroupModelResource\Pages;

use App\Domain\DTO\FileDTO;
use App\Domain\UseCase\Group\GroupUseCaseInterface;
use App\Filament\Resources\GroupModelResource;
use App\Models\GroupModel;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CreateGroupModel extends CreateRecord
{
    protected static string $resource = GroupModelResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $groupUseCase = app(GroupUseCaseInterface::class);

        $fileDTO = null;
        $uploaded = $data['image'] ?? null;

        if ($uploaded instanceof TemporaryUploadedFile) {
            $fileDTO = new FileDTO(
                originalName: $uploaded->getClientOriginalName(),
                extension: $uploaded->getClientOriginalExtension(),
                temporaryPath: $uploaded->getRealPath(),
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
