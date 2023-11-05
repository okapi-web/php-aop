<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ClassHierarchyAspect\Aspect;

use Error;
use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AroundMethodInvocation;
use Okapi\Aop\Tests\Functional\AdviceBehavior\ClassHierarchyAspect\Target\EmailSenderInterface;
use Okapi\Aop\Tests\Functional\AdviceBehavior\ClassHierarchyAspect\Target\SmsSender;

#[Aspect]
class NotificationAspect
{
    #[Around(
        class: EmailSenderInterface::class,
        method: 'send',
    )]
    public function verifyNotSms(AroundMethodInvocation $invocation)
    {
        if ($invocation->getClassName() === SmsSender::class) {
            throw new Error('SmsSender should not be intercepted.');
        }

        return $invocation->proceed();
    }
}
