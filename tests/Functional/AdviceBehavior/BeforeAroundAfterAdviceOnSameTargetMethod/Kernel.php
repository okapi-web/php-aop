<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\BeforeAroundAfterAdviceOnSameTargetMethod;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AdviceBehavior\BeforeAroundAfterAdviceOnSameTargetMethod\Aspect\PaymentProcessorAspect;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        PaymentProcessorAspect::class,
    ];
}
