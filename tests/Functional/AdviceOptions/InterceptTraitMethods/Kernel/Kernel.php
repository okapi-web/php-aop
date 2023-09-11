<?php

namespace Okapi\Aop\Tests\Functional\AdviceOptions\InterceptTraitMethods\Kernel;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AdviceOptions\InterceptTraitMethods\Aspect\InterceptTraitMethodsAspect;
use Okapi\Aop\Tests\Functional\AdviceOptions\InterceptTraitMethods\Aspect\DefaultAspect;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        DefaultAspect::class,
        InterceptTraitMethodsAspect::class,
    ];
}
