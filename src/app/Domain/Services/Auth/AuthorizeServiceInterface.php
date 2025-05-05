<?php

namespace App\Domain\Services\Auth;

use App\Domain\DTO\Policy\GroupIdDTO;
use App\Domain\DTO\Policy\ShoppingListIdDTO;

interface AuthorizeServiceInterface
{
    public function authorizeGroupMember(GroupIdDTO $groupIdDTO): void;
    public function authorizeGroupMemberByShoppingList(ShoppingListIdDTO $shoppingListIdDTO): void;
    public function authorizeGroupOwner(GroupIdDTO $groupIdDTO): void;
}
