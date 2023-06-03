<?php

namespace Okapi\Aop\Tests\Stubs\Kernel\InvalidAspect;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Stubs\Aspect\InvalidAspect\InvalidAspect;

class InvalidAspectClassKernel extends AopKernel
{
    protected array $aspects = [
        InvalidAspect::class,
    ];
}
