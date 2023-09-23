<?php

namespace Okapi\Aop\Tests\Functional\AdviceOptions\OnlyPublicMethods\Kernel;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AdviceOptions\OnlyPublicMethods\Aspect\DefaultAspect;
use Okapi\Aop\Tests\Functional\AdviceOptions\OnlyPublicMethods\Aspect\OnlyPublicMethodsAspect;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        DefaultAspect::class,
        OnlyPublicMethodsAspect::class,
    ];
}
