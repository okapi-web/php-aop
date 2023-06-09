<?php

namespace Okapi\Aop\Tests\Functional\AdviceMatchingMultipleClassesAndMethods\ClassesToIntercept;

class Product
{
    private float $price = 100.00;

    public function getPrice(): float
    {
        return $this->price;
    }
}
