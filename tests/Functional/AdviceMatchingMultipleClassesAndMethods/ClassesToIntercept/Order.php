<?php

namespace Okapi\Aop\Tests\Functional\AdviceMatchingMultipleClassesAndMethods\ClassesToIntercept;

class Order
{
    private float $total = 500.00;

    public function getTotal(): float
    {
        return $this->total;
    }
}
