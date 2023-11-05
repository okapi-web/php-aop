<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ModifyArgument\Target;

class NumberHelper
{
    public function sumArray(array $numbers): int
    {
        return array_sum($numbers);
    }
}
