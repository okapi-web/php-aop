<?php

namespace Okapi\Aop\Tests\Functional\Advice\AdviceBypassTraits;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;

#[Aspect]
class AspectBypassTraits
{
    #[After(
        class: TargetClass::class,
        method: '*',
        bypassTraits: true,
    )]
    public function aspectBypassTraits_validateContent()
    {
        $stackTrace = StackTrace::getInstance();
        $stackTrace->addTrace('AspectBypassTraits');
    }
}
