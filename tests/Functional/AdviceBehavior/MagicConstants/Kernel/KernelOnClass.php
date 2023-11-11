<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\MagicConstants\Kernel;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AdviceBehavior\MagicConstants\Aspect\AspectOnClass;
use Okapi\Aop\Tests\Util;

class KernelOnClass extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        AspectOnClass::class,
    ];
}
