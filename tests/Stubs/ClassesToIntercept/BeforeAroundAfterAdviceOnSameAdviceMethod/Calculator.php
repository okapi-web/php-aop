<?php

namespace Okapi\Aop\Tests\Stubs\ClassesToIntercept\BeforeAroundAfterAdviceOnSameAdviceMethod;

class Calculator
{
    public function add(int $a, int $b): int
    {
        return $a + $b;
    }
}
