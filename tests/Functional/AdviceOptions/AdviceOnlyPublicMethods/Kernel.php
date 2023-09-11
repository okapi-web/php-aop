<?php

namespace Okapi\Aop\Tests\Functional\AdviceOptions\AdviceOnlyPublicMethods;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        AspectDefault::class,
        AspectOnlyPublicMethods::class,
    ];
}
