<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\BeforeAroundAfterAdviceOnSameAdviceMethod\Target;

class Calculator
{
    public function add(int $a, int $b): int
    {
        return $a + $b;
    }
}
