<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ExceptionInsideAdvice;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AdviceBehavior\ExceptionInsideAdvice\Aspect\CommentFilterAspect;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        CommentFilterAspect::class,
    ];
}
