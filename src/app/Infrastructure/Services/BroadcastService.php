<?php

namespace App\Infrastructure\Services;

use App\Domain\Entities\Group;
use App\Domain\Entities\Product;
use App\Domain\Entities\ShoppingList;
use App\Domain\Services\Broadcast\BroadcastServiceInterface;
use App\Events\EventType;
use App\Events\GroupChanged;
use App\Events\ListChanged;
use App\Events\ProductChanged;

class BroadcastService implements BroadcastServiceInterface
{
    public function broadcastProductChanged(Product $product, EventType $eventType): void
    {
        broadcast(new ProductChanged($product, $eventType))->toOthers();
    }

    public function broadcastListChanged(ShoppingList $shoppingList, EventType $eventType): void
    {
        broadcast(new ListChanged($shoppingList, $eventType))->toOthers();
    }

    public function broadcastGroupChanged(Group $group, EventType $eventType): void
    {
        broadcast(new GroupChanged($group, $eventType))->toOthers();
    }
}
