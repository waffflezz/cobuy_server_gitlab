<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\User\UserResource;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'description' => $this->description,
            'status' => $this->status,
            'shoppingListId' => $this->shoppingListId,
            'productImgUrl' => $this->image ? asset('storage/products/' . basename($this->image)) : null,
            'price' => $this->price,
            'buyer' => $this->buyer ? new UserResource($this->buyer) : null,
            'count' => $this->count
        ];
    }
}
