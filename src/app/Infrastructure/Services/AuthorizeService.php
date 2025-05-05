<?php

namespace App\Infrastructure\Services;

use App\Domain\DTO\Policy\GroupIdDTO;
use App\Domain\DTO\Policy\ShoppingListIdDTO;
use App\Domain\Services\Auth\AuthorizeServiceInterface;
use Illuminate\Support\Facades\Gate;

class AuthorizeService implements AuthorizeServiceInterface
{
    public function authorizeGroupMember(GroupIdDTO $groupIdDTO): void
    {
        Gate::authorize('groupMember', $groupIdDTO);
    }

    public function authorizeGroupOwner(GroupIdDTO $groupIdDTO): void
    {
        Gate::authorize('groupOwner', $groupIdDTO);
    }

    public function authorizeGroupMemberByShoppingList(ShoppingListIdDTO $shoppingListIdDTO): void
    {
        Gate::authorize('groupMemberByShoppingList', $shoppingListIdDTO);
    }
}
