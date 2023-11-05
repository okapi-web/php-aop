<?php

namespace Okapi\Aop\Tests\Functional\ErrorHandling\InvalidAspect\Kernel;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\ErrorHandling\InvalidAspect\Aspect\InvalidAspect;

class InvalidAspectClassKernel extends AopKernel
{
    protected array $aspects = [
        InvalidAspect::class,
    ];
}
