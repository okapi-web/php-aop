<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\AfterThrowing;

use Okapi\Aop\Invocation\AfterThrowingMethodInvocation;
use Throwable;

class State
{
    /** @var list<string> */
    public static array $events = [];

    /** @var list<AfterThrowingMethodInvocation> */
    public static array $invocations = [];

    public static ?Throwable $observerFailure = null;
}
