<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\MagicConstants\Kernel;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AdviceBehavior\MagicConstants\Aspect\AspectOnParent;
use Okapi\Aop\Tests\Util;

class KernelOnParent extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        AspectOnParent::class,
    ];
}
