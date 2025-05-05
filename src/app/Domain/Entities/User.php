<?php

namespace App\Domain\Entities;

use DateTime;

class User extends BaseEntity
{
    /**
     * @param int $id
     * @param string $name
     * @param string $email
     * @param DateTime|null $emailVerifiedAt
     * @param string $password
     * @param string|null $rememberToken
     * @param DateTime $createdAt
     * @param DateTime $updatedAt
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?DateTime $emailVerifiedAt,
        public string $password,
        public ?string $rememberToken,
        public DateTime $createdAt,
        public DateTime $updatedAt
    ) {}

//    public function toArray(): array
//    {
//        return [
//            'id' => $this->id,
//            'name' => $this->name,
//            'email' => $this->email,
//            'email_verified_at' => $this->emailVerifiedAt,
//            'password' => $this->password,
//            'remember_token' => $this->rememberToken,
//            'created_at' => $this->createdAt,
//            'updated_at' => $this->updatedAt
//        ];
//    }
}
