<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\Include;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AdviceBehavior\Include\Aspect\DatabaseModifierAspect;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        DatabaseModifierAspect::class,
    ];
}
