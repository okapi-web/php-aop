<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ClassHierarchyAspect;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AdviceBehavior\ClassHierarchyAspect\Aspect\NotificationAspect;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        NotificationAspect::class,
    ];
}
