<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\AfterThrowing;

use Throwable;

class FilteredTarget
{
    public function fail(Throwable $throwable): void
    {
        throw $throwable;
    }

    public function runtimeOnly(Throwable $throwable): void
    {
        throw $throwable;
    }
}
