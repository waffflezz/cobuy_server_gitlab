<?php

namespace App\Services;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GroupService
{
    public function getGroupByUser(UserModel $user, string $groupId)
    {
        $group = $user->groups()->find($groupId);
        if (!$group) {
            throw new ModelNotFoundException('GroupModel not found by ID: ' . $groupId);
        }

        return $group;
    }
}
