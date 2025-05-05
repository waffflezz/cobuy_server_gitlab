<?php

namespace App\Models;

use App\Domain\Entities\ShoppingList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShoppingListModel extends Model
{
    use HasFactory;

    protected $table = 'shopping_lists';

    protected $fillable = [
        'name',
        'group_id',
        'hidden'
    ];

    protected $casts = [
        'hidden' => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(GroupModel::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(ProductModel::class, 'shopping_list_id');
    }

    public function toDomain(): ShoppingList
    {
        return new ShoppingList(
            $this->id,
            $this->name,
            $this->group_id,
            new \DateTime($this->created_at),
            new \DateTime($this->updated_at),
            $this->getAttribute('hidden'),
            $this->products->map(fn(ProductModel $productModel) => $productModel->toDomain())->all()
        );
    }
}
