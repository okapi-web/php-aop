<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\MissingClassOrMethod\ClassesToIntercept;

class InventoryManager
{
    private array $items = [];

    public function addItem(string $itemName, int $quantity): void
    {
        $this->items[$itemName] = $quantity;
    }

    public function removeItem(string $itemName): void
    {
        unset($this->items[$itemName]);
    }

    public function getQuantity(string $itemName): int
    {
        return $this->items[$itemName] ?? 0;
    }
}
