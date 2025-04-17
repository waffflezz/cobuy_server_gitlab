<?php

namespace App\Policies;

use App\Models\GroupModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Log;

class ShoppingListPolicy
{
    public function groupMember(UserModel $user, GroupModel $group): bool
    {
        return $group->users->contains($user);
    }

    public function groupOwner(UserModel $user, GroupModel $group): bool
    {
        return $user->id === $group->owner_id;
    }

}
