<?php

namespace App\Domain\Entities;

class ShoppingList extends BaseEntity
{
    public function __construct(
        public int $id,
        public string $name,
        public int $groupId,
        public \DateTime $createdAt,
        public \DateTime $updatedAt,
        public bool $hidden,
        public array $products = []
    ) {}
}
