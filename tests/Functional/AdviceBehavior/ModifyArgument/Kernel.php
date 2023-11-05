<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ModifyArgument;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AdviceBehavior\ModifyArgument\Aspect\NumberHelperAspect;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        NumberHelperAspect::class,
    ];
}
