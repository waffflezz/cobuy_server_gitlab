<?php

namespace App\Domain\UseCase\Group;

use App\Domain\Entities\Group;

interface GroupRepositoryInterface
{
    /**
     * @return Group[]
     */
    public function findAll(int $userId): array;
    public function create(int $userId, string $name, ?string $image): Group;
}
