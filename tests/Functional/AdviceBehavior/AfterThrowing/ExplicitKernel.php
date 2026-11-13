<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\AfterThrowing;

class ExplicitKernel extends Kernel
{
    /** @var array<array-key, class-string> */
    protected array $aspects = [];
}
