<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\MultipleAdvicesWithSameAdviceTypeOnSameTargetMethod;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AdviceBehavior\MultipleAdvicesWithSameAdviceTypeOnSameTargetMethod\Aspect\ProfilePictureValidatorAspect;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        ProfilePictureValidatorAspect::class,
    ];
}
