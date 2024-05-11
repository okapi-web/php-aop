<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\NewClassCreationWithProxiedClasses;

use Okapi\Aop\Tests\Functional\AdviceBehavior\NewClassCreationWithProxiedClasses\Aspect\ModifyGroupPolicyAspect;
use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        ModifyGroupPolicyAspect::class,
    ];
}
