<?php

namespace App\Http\Resources;

use App\Domain\Entities\Product;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShoppingListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'groupId' => $this->groupId,
            'productsCount' => count($this->products),
            'checkedProductsCount' => count(array_filter($this->products, function (Product $product) {
                return $product->status != 0;
            })),
            'hidden' => $this->hidden
        ];
    }
}
