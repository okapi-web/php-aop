<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\Readonly;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AdviceBehavior\Readonly\Aspect\ReadonlyAspect;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        ReadonlyAspect::class,
    ];
}
