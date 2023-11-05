<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\InterfaceAdvice;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AdviceBehavior\InterfaceAdvice\Aspect\UserInterfaceAspect;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        UserInterfaceAspect::class,
    ];
}
