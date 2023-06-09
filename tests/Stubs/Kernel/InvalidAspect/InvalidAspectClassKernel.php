<?php

namespace Okapi\Aop\Tests\Stubs\Kernel\InvalidAspect;

use Okapi\Aop\AopKernel;

class InvalidAspectClassKernel extends AopKernel
{
    protected array $aspects = [
        \Okapi\Aop\Tests\Functional\InvalidAspect\Aspect\InvalidAspect::class,
    ];
}
