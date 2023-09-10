<?php

namespace Okapi\Aop\Tests\Functional\Advice\AdviceBypassParents;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;

#[Aspect]
class AspectBypassParents
{
    #[After(
        class: TargetClass::class,
        method: '*',
        bypassParent: true,
    )]
    public function aspectBypassParents_validateContent()
    {
        $stackTrace = StackTrace::getInstance();
        $stackTrace->addTrace('AspectBypassParents');
    }
}
