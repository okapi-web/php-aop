<?php

namespace Okapi\Aop\Tests\Functional\AdviceOptions\AdviceOnlyPublicMethods;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;
use Okapi\Aop\Invocation\AfterMethodInvocation ;

#[Aspect]
class AspectOnlyPublicMethods
{
    #[After(
        class: 'Okapi\Aop\Tests\Functional\AdviceOptions\AdviceOnlyPublicMethods\Target*',
        method: '*',
        onlyPublicMethods: true,
    )]
    public function validateContent(AfterMethodInvocation $invocation)
    {
        $stackTrace = StackTrace::getInstance();
        $stackTrace->addTrace('AspectOnlyPublicMethods '.$invocation->getMethodName());
    }
}
