<?php

namespace Okapi\Aop\Tests\Functional\AspectMatching\AdviceMatchingMultipleClassesAndMethods\Target;

class Product
{
    private float $price = 100.00;

    public function getPrice(): float
    {
        return $this->price;
    }
}
