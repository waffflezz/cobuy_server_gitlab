<?php

namespace App\Models;

use App\Domain\Entities\Group;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupModel extends Model
{
    use HasFactory;

    protected $table = 'groups';

    protected $fillable = [
        'name',
        'image',
        'owner_id'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(UserModel::class, 'group_user', 'group_id', 'user_id');
    }
    public function owner(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'owner_id');
    }

    public function shoppingLists(): HasMany
    {
        return $this->hasMany(ShoppingListModel::class, 'group_id');
    }

    public function toDomain(): Group
    {
        return new Group(
            $this->id,
            $this->name,
            $this->iamge,
            $this->owner_id,
            new \DateTime($this->created_at),
            new \DateTime($this->updated_at),
            $this->invite_link,
            $this->users->map(fn(UserModel $userModel) => $userModel->toDomain())->all(),
            $this->shoppingLists->map(fn(ShoppingListModel $shoppingListModel) => $shoppingListModel->toDomain())->all(),
        );
    }
}
