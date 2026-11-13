<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\AfterThrowing;

use Throwable;

class MethodTarget
{
    #[ExplicitAspect]
    public function fail(Throwable $exception): void
    {
        throw $exception;
    }
}
