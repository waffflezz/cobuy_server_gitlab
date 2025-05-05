<?php

namespace App\Domain\Services\Auth;

use App\Domain\DTO\Policy\GroupIdDTO;

interface AuthorizeServiceInterface
{
    public function authorizeGroupMember(GroupIdDTO $groupIdDTO): void;

    public function authorizeGroupOwner(GroupIdDTO $groupIdDTO): void;
}
