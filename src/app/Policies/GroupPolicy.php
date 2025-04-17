<?php

namespace App\Policies;

use App\Models\GroupModel;
use App\Models\Product;
use App\Models\ShoppingList;
use App\Models\UserModel;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class GroupPolicy
{
    public function groupMember(UserModel $user, GroupModel $group): bool
    {
        return $group->users->contains($user);
    }

    public function groupOwner(UserModel $user, GroupModel $group): bool
    {
        return (int) $user->id === (int) $group->owner_id;
    }
}
