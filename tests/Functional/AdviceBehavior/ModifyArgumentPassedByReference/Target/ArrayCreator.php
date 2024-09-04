<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ModifyArgumentPassedByReference\Target;

class ArrayCreator
{
    public function createArray(mixed &$data): void
    {
        $data = ['data' => $data];
    }
}
