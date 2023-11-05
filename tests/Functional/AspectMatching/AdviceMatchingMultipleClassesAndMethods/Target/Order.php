<?php

namespace Okapi\Aop\Tests\Functional\AspectMatching\AdviceMatchingMultipleClassesAndMethods\Target;

class Order
{
    private float $total = 500.00;

    public function getTotal(): float
    {
        return $this->total;
    }
}
