<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\AfterThrowing;

use Attribute;
use Okapi\Aop\Attributes\AfterThrowing;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AfterThrowingMethodInvocation;
use RuntimeException;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
#[Aspect]
class ExplicitAspect
{
    #[AfterThrowing(exception: RuntimeException::class)]
    public function observe(AfterThrowingMethodInvocation $invocation): void
    {
        State::$invocations[] = $invocation;
    }
}
