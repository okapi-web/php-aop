<?php

namespace Okapi\Aop\Tests\Functional\Advice\AdviceOnlyPublic;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Util;

class TargetKernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        AspectDefault::class,
        AspectOnlyPublic::class,
    ];
}
