<?php

namespace Okapi\Aop\Tests\Functional\ErrorHandling\MissingClassOrMethod\Kernel;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\ErrorHandling\MissingClassOrMethod\Aspect\GetQuantityLoggerAspect;
use Okapi\Aop\Tests\Util;

class GetQuantityKernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        GetQuantityLoggerAspect::class,
    ];
}
