<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\BeforeAroundAfterAdviceOnSameAdviceMethod;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AdviceBehavior\BeforeAroundAfterAdviceOnSameAdviceMethod\Aspect\CalculatorLoggerAspect;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        CalculatorLoggerAspect::class,
    ];
}
