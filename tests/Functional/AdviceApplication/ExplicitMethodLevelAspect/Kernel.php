<?php

namespace Okapi\Aop\Tests\Functional\AdviceApplication\ExplicitMethodLevelAspect;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AdviceApplication\ExplicitMethodLevelAspect\Aspect\PerformanceAspect;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        PerformanceAspect::class,
    ];
}
