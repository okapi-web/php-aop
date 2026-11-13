<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\AfterThrowing;

use LogicException;
use Okapi\Aop\Attributes\AfterThrowing;
use Okapi\Aop\Attributes\Aspect;
use RuntimeException;

#[Aspect]
class RepeatedAspect
{
    #[AfterThrowing(class: RepeatedTarget::class, method: 'first', order: 20, exception: RuntimeException::class)]
    #[AfterThrowing(class: RepeatedTarget::class, method: 'second', order: -20, exception: LogicException::class)]
    public function scoped(): void
    {
        State::$events[] = 'scoped';
    }

    #[AfterThrowing(class: RepeatedTarget::class, method: 'ordered', order: 20, exception: RuntimeException::class)]
    #[AfterThrowing(class: RepeatedTarget::class, method: 'ordered', order: -20, exception: LogicException::class)]
    public function ordered(): void
    {
        State::$events[] = 'ordered';
    }

    #[AfterThrowing(class: RepeatedTarget::class, method: 'first|second|ordered', order: 0)]
    public function middle(): void
    {
        State::$events[] = 'middle';
    }
}
