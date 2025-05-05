<?php

namespace App\Domain\Entities;

class Product extends BaseEntity
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public int $shoppingListId,
        public \DateTime $createdAt,
        public \DateTime $updatedAt,
        public int $status,
        public ?string $image,
        public ?int $price,
        public ?User $buyer,
        public ?int $count
    ) {}

    const NONE_STATUS = 0;
    const BUY_STATUS = 1;
    const PLANNED_STATUS = 2;
}
