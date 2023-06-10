<?php

namespace Okapi\Aop\Tests\Functional\ExplicitClassLevelAspect\ClassesToMatch;

use Okapi\Aop\Tests\Functional\ExplicitClassLevelAspect\Aspect\LoggingAspect;

#[LoggingAspect]
class InventoryTracker
{
    private array $inventory = [];

    public function updateInventory(int $productId, int $quantity): void
    {
        $this->inventory[$productId] = $quantity;
    }

    public function checkInventory(int $productId): int
    {
        return $this->inventory[$productId] ?? 0;
    }
}
