<?php

namespace App\Domain\Entities;

use DateTime;

class User
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?DateTime $emailVerifiedAt,
        public string $password,
        public ?string $rememberToken,
        public DateTime $createdAt,
        public DateTime $updatedAt,
    ) {}
}
