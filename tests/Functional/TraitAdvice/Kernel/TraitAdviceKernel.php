<?php

namespace Okapi\Aop\Tests\Functional\TraitAdvice\Kernel;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\TraitAdvice\Aspect\RouteCachingAspect;
use Okapi\Aop\Tests\Util;

class TraitAdviceKernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        RouteCachingAspect::class,
    ];
}
