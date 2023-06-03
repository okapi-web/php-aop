<?php

namespace Okapi\Aop\Tests\Stubs\ClassesToIntercept\AdviceMatchingMultipleClassesAndMethods;

class Product
{
    private float $price = 100.00;

    public function getPrice(): float
    {
        return $this->price;
    }
}
