<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\UserModel;
use Illuminate\Support\Facades\Log;

class ShoppingListPolicy
{
    public function groupMember(UserModel $user, Group $group): bool
    {
        return $group->users->contains($user);
    }

    public function groupOwner(UserModel $user, Group $group): bool
    {
        return $user->id === $group->owner_id;
    }

}
