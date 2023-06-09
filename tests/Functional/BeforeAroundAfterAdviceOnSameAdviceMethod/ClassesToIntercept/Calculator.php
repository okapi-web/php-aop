<?php

namespace Okapi\Aop\Tests\Functional\BeforeAroundAfterAdviceOnSameAdviceMethod\ClassesToIntercept;

class Calculator
{
    public function add(int $a, int $b): int
    {
        return $a + $b;
    }
}
