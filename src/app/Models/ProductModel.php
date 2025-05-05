<?php

namespace App\Models;

use App\Domain\Entities\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductModel extends Model
{
    use HasFactory;

    protected $table = 'products';

    const NONE_STATUS = 0;
    const BUY_STATUS = 1;
    const PLANNED_STATUS = 2;

    protected $fillable = [
        'name',
        'description',
        'status',
        'shopping_list_id',
        'image',
        'price',
        'user_id',
        'count',
        'buyer_id'
    ];

    public function shoppingList(): BelongsTo
    {
        return $this->belongsTo(ShoppingListModel::class, 'shopping_list_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'buyer_id');
    }

    public function toDomain(): Product
    {
        return new Product(
            $this->id,
            $this->name,
            $this->description,
            $this->shopping_list_id,
            new \DateTime($this->created_at),
            new \DateTime($this->updated_at),
            $this->status,
            $this->image,
            $this->price,
            $this->buyer?->toDomain(),
            $this->count
        );
    }
}
