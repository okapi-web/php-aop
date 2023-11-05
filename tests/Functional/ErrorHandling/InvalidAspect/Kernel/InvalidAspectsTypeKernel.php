<?php

namespace Okapi\Aop\Tests\Functional\ErrorHandling\InvalidAspect\Kernel;

use Okapi\Aop\AopKernel;

class InvalidAspectsTypeKernel extends AopKernel
{
    protected array $aspects = [
        42,
    ];
}
