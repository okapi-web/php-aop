<?php

namespace Okapi\Aop\Tests\Stubs\Kernel;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Stubs\Aspects\AfterHelloWorldAspect;
use Okapi\Aop\Tests\Util;

class ApplicationKernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected bool $debug = true; // TODO: remove

    protected array $aspects = [
        AfterHelloWorldAspect::class,
    ];
}
