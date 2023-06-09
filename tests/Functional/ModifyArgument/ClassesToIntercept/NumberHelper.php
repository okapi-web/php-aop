<?php

namespace Okapi\Aop\Tests\Functional\ModifyArgument\ClassesToIntercept;

class NumberHelper
{
    public function sumArray(array $numbers): int
    {
        return array_sum($numbers);
    }
}
