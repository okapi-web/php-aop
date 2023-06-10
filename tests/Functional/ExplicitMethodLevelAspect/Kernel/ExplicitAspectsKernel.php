<?php

namespace Okapi\Aop\Tests\Functional\ExplicitMethodLevelAspect\Kernel;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\ExplicitMethodLevelAspect\Aspect\PerformanceAspect;
use Okapi\Aop\Tests\Util;

class ExplicitAspectsKernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        PerformanceAspect::class,
    ];
}
