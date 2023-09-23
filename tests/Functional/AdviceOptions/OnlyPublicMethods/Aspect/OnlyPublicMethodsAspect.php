<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\AdviceOptions\OnlyPublicMethods\Aspect;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;

#[Aspect]
class OnlyPublicMethodsAspect
{
    #[After(
        class: 'Okapi\Aop\Tests\Functional\AdviceOptions\OnlyPublicMethods\Target\Target*',
        method: '*',
        onlyPublicMethods: true,
    )]
    public function validateContent(AfterMethodInvocation $invocation): void
    {
        $stackTrace = StackTrace::getInstance();
        $stackTrace->addTrace('OnlyPublicMethodsAspect '.$invocation->getMethodName());
    }
}
