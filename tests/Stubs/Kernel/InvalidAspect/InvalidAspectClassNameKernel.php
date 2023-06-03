<?php

namespace Okapi\Aop\Tests\Stubs\Kernel\InvalidAspect;

use Okapi\Aop\AopKernel;

class InvalidAspectClassNameKernel extends AopKernel
{
    protected array $aspects = [
        'InvalidAspect',
    ];
}
