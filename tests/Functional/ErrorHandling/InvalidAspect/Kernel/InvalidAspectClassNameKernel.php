<?php

namespace Okapi\Aop\Tests\Functional\ErrorHandling\InvalidAspect\Kernel;

use Okapi\Aop\AopKernel;

class InvalidAspectClassNameKernel extends AopKernel
{
    protected array $aspects = [
        'InvalidAspect',
    ];
}
