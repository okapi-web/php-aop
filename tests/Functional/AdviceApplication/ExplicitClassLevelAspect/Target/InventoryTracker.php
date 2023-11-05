<?php

namespace Okapi\Aop\Tests\Functional\AdviceApplication\ExplicitClassLevelAspect\Target;

use Okapi\Aop\Tests\Functional\AdviceApplication\ExplicitClassLevelAspect\Aspect\LoggingAspect;

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
