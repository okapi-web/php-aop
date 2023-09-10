<?php

namespace Okapi\Aop\Tests\Functional\Advice\AdviceBypassTraits;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;

#[Aspect]
class AspectDefault
{
    #[After(
        class: TargetClass::class,
        method: '*',
    )]
    public function aspectDefault_validateContent()
    {
        $stackTrace = StackTrace::getInstance();
        $stackTrace->addTrace('AspectDefault');
    }
}
