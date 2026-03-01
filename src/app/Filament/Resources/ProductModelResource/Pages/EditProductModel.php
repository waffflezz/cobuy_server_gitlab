<?php

namespace App\Filament\Resources\ProductModelResource\Pages;

use App\Domain\DTO\FileDTO;
use App\Domain\UseCase\Product\ProductUseCaseInterface;
use App\Filament\Resources\ProductModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EditProductModel extends EditRecord
{
    protected static string $resource = ProductModelResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $productUseCase = app(ProductUseCaseInterface::class);

        $userId = Auth::id();

        $shoppingListId = (int) $record->shoppingList->id;
        $productId = (int) $record->getKey();

        $fileDTO = null;
        $uploaded = $data["image"] ?? null;

        if ($uploaded instanceof TemporaryUploadedFile) {
            $fileDTO = new FileDTO(
                originalName: $uploaded->getClientOriginalName(),
                extension: $uploaded->getClientOriginalExtension(),
                temporaryPath: $uploaded->getRealPath(),
            );
            unset($data["image"]);
        }

        $productUseCase->update(
            $userId,
            $shoppingListId,
            $productId,
            $data,
            $fileDTO,
        );

        return $record->fresh() ?? $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
