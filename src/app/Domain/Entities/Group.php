<?php

namespace App\Domain\Entities;

class Group
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $image,
        public int $ownerId,
        public \DateTime $createdAt,
        public \DateTime $updatedAt,
        public array $users = [],
        public array $shoppingLists = [],
        public ?string $invite_link,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'owner_id' => $this->ownerId,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'users' => array_map(fn(User $user) => $user->toArray(), $this->users),
            'shoppingLists' => [],
            'invite_link' => $this->invite_link,
        ];
    }
}
