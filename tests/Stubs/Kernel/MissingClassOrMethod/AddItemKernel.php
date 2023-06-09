<?php

namespace Okapi\Aop\Tests\Stubs\Kernel\MissingClassOrMethod;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\MissingClassOrMethod\Aspect\AddItemLoggerAspect;
use Okapi\Aop\Tests\Util;

class AddItemKernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        AddItemLoggerAspect::class,
    ];
}
