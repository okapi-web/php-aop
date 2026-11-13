<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\AfterThrowing;

use Throwable;

class RepeatedTarget
{
    public function first(Throwable $throwable): void
    {
        throw $throwable;
    }

    public function second(Throwable $throwable): void
    {
        throw $throwable;
    }

    public function ordered(Throwable $throwable): void
    {
        throw $throwable;
    }
}
