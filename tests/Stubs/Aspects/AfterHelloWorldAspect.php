<?php

namespace Okapi\Aop\Tests\Stubs\Aspects;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Intercept\AfterMethodInvocation;
use Okapi\Aop\Tests\Stubs\ClassesToIntercept\HelloWorldClass;

#[Aspect]
class AfterHelloWorldAspect
{
    #[After(
        class: 'Okapi*Tests*ClassesToIntercept\HelloWorldClass',
        method: 'test',
    )]
    public function toUpperCase(AfterMethodInvocation $invocation): string
    {
        $result = $invocation->proceed();
        return strtoupper($result);
    }
}
