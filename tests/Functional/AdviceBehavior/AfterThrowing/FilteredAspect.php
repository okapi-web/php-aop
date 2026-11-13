<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\AfterThrowing;

use Error;
use Exception;
use Okapi\Aop\Attributes\AfterThrowing;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AfterThrowingMethodInvocation;
use RuntimeException;
use Throwable;

#[Aspect]
class FilteredAspect
{
    #[AfterThrowing(class: FilteredTarget::class, method: 'fail', order: -20, exception: RuntimeException::class)]
    public function runtime(AfterThrowingMethodInvocation $invocation): void
    {
        State::$events[] = 'runtime';
        State::$invocations[] = $invocation;
    }

    #[AfterThrowing(class: FilteredTarget::class, method: 'fail', order: -10, exception: Exception::class)]
    public function exception(): void
    {
        State::$events[] = 'exception';
    }

    #[AfterThrowing(class: FilteredTarget::class, method: 'fail', order: 10, exception: Error::class)]
    public function error(): void
    {
        State::$events[] = 'error';
    }

    #[AfterThrowing(class: FilteredTarget::class, method: 'fail', order: 20, exception: Throwable::class)]
    public function throwable(AfterThrowingMethodInvocation $invocation): void
    {
        State::$events[] = 'throwable';
        State::$invocations[] = $invocation;
    }

    #[AfterThrowing(class: FilteredTarget::class, method: 'runtimeOnly', exception: RuntimeException::class)]
    public function runtimeOnly(): void
    {
        State::$events[] = 'runtimeOnly';
    }
}
