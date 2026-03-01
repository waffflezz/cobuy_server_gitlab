<?php

namespace App\Filament\Resources\ProductModelResource\Pages;

use App\Domain\DTO\FileDTO;
use App\Domain\UseCase\Product\ProductUseCaseInterface;
use App\Filament\Resources\ProductModelResource;
use App\Models\ProductModel;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CreateProductModel extends CreateRecord
{
    protected static string $resource = ProductModelResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $productUseCase = app(ProductUseCaseInterface::class);

        $shoppingListId = (int) $data["shopping_list_id"];
        unset($data['shopping_list_id']);

        $fileDTO = null;

        $uploaded = $data["image"] ?? null;

        if ($uploaded instanceof TemporaryUploadedFile) {
            $fileDTO = new FileDTO(
                originalName: $uploaded->getClientOriginalName(),
                extension: $uploaded->getClientOriginalExtension(),
                temporaryPath: $uploaded->getRealPath(),
            );
        }

        unset($data["image"]);

        $domainProduct = $productUseCase->create(
            Auth::id(),
            $shoppingListId,
            $data,
            $fileDTO,
        );

        return ProductModel::query()->findOrFail($domainProduct->id);
    }
}
