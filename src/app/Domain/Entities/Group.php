<?php

namespace App\Domain\Entities;

use DateTime;

class Group extends BaseEntity
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $image,
        public int $ownerId,
        public DateTime $createdAt,
        public DateTime $updatedAt,
        public array $users = [],
        public array $shoppingLists = [],
        public ?string $inviteLink,
    ) {}

//    public function toArray(): array
//    {
//        return [
//            'id' => $this->id,
//            'name' => $this->name,
//            'image' => $this->image,
//            'owner_id' => $this->ownerId,
//            'created_at' => $this->createdAt,
//            'updated_at' => $this->updatedAt,
//            'users' => array_map(fn(User $user) => $user->toArray(), $this->users),
//            'shopping_lists' => [],
//            'invite_link' => $this->inviteLink,
//        ];
//    }
}
