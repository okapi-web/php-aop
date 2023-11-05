<?php

namespace Okapi\Aop\Tests\Functional\AdviceApplication\AdviceMatchingAbstractMethod;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AdviceApplication\AdviceMatchingAbstractMethod\Aspect\FileUploaderAspect;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        FileUploaderAspect::class,
    ];
}
