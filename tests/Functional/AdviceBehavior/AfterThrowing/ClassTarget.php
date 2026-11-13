<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\AfterThrowing;

use Throwable;

#[ExplicitAspect]
class ClassTarget
{
    public function fail(Throwable $exception): void
    {
        throw $exception;
    }
}
