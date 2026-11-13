<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\AfterThrowing;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\AfterThrowing;
use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Attributes\Before;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Invocation\AfterThrowingMethodInvocation;
use Okapi\Aop\Invocation\AroundMethodInvocation;
use Okapi\Aop\Invocation\BeforeMethodInvocation;
use RuntimeException;
use Throwable;

#[Aspect]
class FailureAspect
{
    #[AfterThrowing(class: Target::class, method: 'run|failStatic|failNever', order: 10)]
    public function late(AfterThrowingMethodInvocation $invocation): void
    {
        State::$events[] = 'late';
        State::$invocations[] = $invocation;
    }

    #[AfterThrowing(class: Target::class, method: 'run|failStatic|failNever', order: -10)]
    public function early(AfterThrowingMethodInvocation $invocation): string
    {
        State::$events[] = 'early';
        State::$invocations[] = $invocation;
        if (State::$observerFailure !== null) {
            throw State::$observerFailure;
        }
        return 'ignored';
    }

    #[Before(class: Target::class, method: 'run')]
    public function before(BeforeMethodInvocation $invocation): void
    {
        if ($invocation->getArgument('mode') === 'before') {
            throw new RuntimeException('before');
        }
        $invocation->setArgument('label', 'updated');
    }

    #[Around(class: Target::class, method: 'run')]
    public function around(AroundMethodInvocation $invocation): void
    {
        /** @var mixed $mode */
        $mode = $invocation->getArgument('mode');
        if ($mode === 'around') {
            throw new RuntimeException('around');
        }
        if ($mode === 'short') {
            $invocation->setResult('short');
            return;
        }
        if ($mode === 'recover' || $mode === 'retry') {
            try {
                $invocation->proceed();
            } catch (Throwable) {
                State::$events[] = 'caught';
                if ($mode === 'retry') {
                    $invocation->proceed(true);
                    return;
                }
                $invocation->setResult('recovered');
            }
        }
    }

    #[After(class: Target::class, method: 'run')]
    public function after(AfterMethodInvocation $invocation): void
    {
        State::$events[] = 'after';
        if ($invocation->getArgument('mode') === 'after') {
            throw new RuntimeException('after');
        }
    }
}
