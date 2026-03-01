<?php

namespace App\Filament\Resources\GroupModelResource\Pages;

use App\Domain\UseCase\Group\GroupUseCaseInterface;
use App\Filament\Resources\GroupModelResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditGroupModel extends EditRecord
{
    protected static string $resource = GroupModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generateInviteLink')
                ->label('Сгенерировать invite ссылку')
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
                        ->title('Invite ссылка сгенерирована')
                        ->success()
                        ->send();
                }),

            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
