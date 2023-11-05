<?php

namespace Okapi\Aop\Tests\Functional\AspectMatching\SelfType;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AspectMatching\SelfType\Aspect\SalaryIncreaserAspect;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        SalaryIncreaserAspect::class,
    ];
}
