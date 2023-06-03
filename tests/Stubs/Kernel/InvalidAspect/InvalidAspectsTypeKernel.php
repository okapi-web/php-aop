<?php

namespace Okapi\Aop\Tests\Stubs\Kernel\InvalidAspect;

use Okapi\Aop\AopKernel;

class InvalidAspectsTypeKernel extends AopKernel
{
    protected array $aspects = [
        42,
    ];
}
