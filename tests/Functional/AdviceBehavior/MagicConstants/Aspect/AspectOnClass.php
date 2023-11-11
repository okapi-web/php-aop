<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\MagicConstants\Aspect;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Tests\Functional\AdviceBehavior\MagicConstants\Target\TargetClass;

#[Aspect]
class AspectOnClass
{
    #[After(
        class: TargetClass::class . '*',
        method: '*'
    )]
    public function doNothing(): void {}
}
