<?php

namespace App\Domain\UseCase\Group;

use App\Domain\DTO\FileDTO;
use App\Domain\Entities\Group;

interface GroupUseCaseInterface
{
    /**
     * @param int $userId
     * @return Group[]
     */
    public function getAll(int $userId): array;
    public function create(int $userId, string $name, ?FileDTO $fileDTO): Group;
}
