<?php

namespace App\Domain\Services\Broadcast;

use App\Domain\Entities\Group;
use App\Domain\Entities\Product;
use App\Domain\Entities\ShoppingList;
use App\Events\EventType;

interface BroadcastServiceInterface
{
    public function broadcastProductChanged(Product $product, EventType $eventType): void;
    public function broadcastListChanged(ShoppingList $shoppingList, EventType $eventType): void;
    public function broadcastGroupChanged(Group $group, EventType $eventType): void;
}
