<?php

namespace Okapi\Aop\Tests\Performance\Kernel;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Util;

class MeasurePerformanceKernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    /** @noinspection PhpFullyQualifiedNameUsageInspection */
    protected array $aspects = [
        \Okapi\Aop\Tests\Performance\Aspect\AddOneAspect::class,
    ];
}
