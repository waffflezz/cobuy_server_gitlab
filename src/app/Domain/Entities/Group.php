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
        public ?string $inviteLink,
        public array $users = [],
        public array $shoppingLists = [],
    ) {}
}
