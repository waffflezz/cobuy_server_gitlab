<?php

namespace App\Http\Resources\Group;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'avaUrl' => $this->image ? asset('storage/groups/' . basename($this->image)) : null,
            'inviteLink' => $this->inviteLink,
            'owner' => $this->ownerId,
            'membersCount' => count($this->users),
            'listsCount' => count($this->shoppingLists),
            'members' => UserResource::collection($this->users)
        ];
    }
}
