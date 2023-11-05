<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\AdviceBehavior\OnlyPublicMethods\Aspect;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;

#[Aspect]
class DefaultAspect
{
    #[After(
        class: 'Okapi\Aop\Tests\Functional\AdviceBehavior\OnlyPublicMethods\Target\Target*',
        method: '*',
    )]
    public function validateContent(AfterMethodInvocation $invocation): void
    {
        $stackTrace = StackTrace::getInstance();
        $stackTrace->addTrace('DefaultAspect '.$invocation->getMethodName());
    }
}
