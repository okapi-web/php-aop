<?php

namespace Okapi\Aop\Tests\Functional\ExplicitMethodLevelAspect\Kernel;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Util;

class EmptyKernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [];
}
