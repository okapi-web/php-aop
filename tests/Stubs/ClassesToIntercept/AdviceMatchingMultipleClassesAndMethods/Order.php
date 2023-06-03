<?php

namespace Okapi\Aop\Tests\Stubs\ClassesToIntercept\AdviceMatchingMultipleClassesAndMethods;

class Order
{
    private float $total = 500.00;

    public function getTotal(): float
    {
        return $this->total;
    }
}
