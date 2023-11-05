<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\AdviceOrder;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AdviceBehavior\AdviceOrder\Aspect\ArticleModerationAspect;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        ArticleModerationAspect::class,
    ];
}
