<?php

namespace Okapi\Aop\Tests\Stubs\ClassesToIntercept\ModifyArgument;

class NumberHelper
{
    public function sumArray(array $numbers): int
    {
        return array_sum($numbers);
    }
}
