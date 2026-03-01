<?php

namespace App\Filament\Resources\GroupModelResource\Pages;

use App\Domain\DTO\FileDTO;
use App\Domain\UseCase\Group\GroupUseCaseInterface;
use App\Filament\Resources\GroupModelResource;
use App\Models\GroupModel;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EditGroupModel extends EditRecord
{
    protected static string $resource = GroupModelResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
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

        $groupUseCase->update(
            (string) $record->getKey(),
            $data,
            $fileDTO,
        );

        return $record->fresh() ?? $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generateInviteLink')
                ->label('Сгенерировать invite токен')
                ->icon('heroicon-o-link')
                ->action(function (GroupUseCaseInterface $groupUseCase): void {
                    $groupId = (string) $this->record->getKey();
                    $groupUseCase->getInviteLink($this->record->owner->id, $groupId);

                    $this->record->refresh();

                    $this->form->fill([
                        ...$this->form->getState(),
                        'invite_link' => $this->record->invite_link,
                    ]);

                    Notification::make()
                        ->title('Invite токен сгенерирован')
                        ->success()
                        ->send();
                }),

            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
