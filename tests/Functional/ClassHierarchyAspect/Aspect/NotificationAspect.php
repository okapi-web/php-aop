<?php

namespace Okapi\Aop\Tests\Functional\ClassHierarchyAspect\Aspect;

use Error;
use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AroundMethodInvocation;
use Okapi\Aop\Tests\Functional\ClassHierarchyAspect\ClassesToIntercept\EmailSenderInterface;
use Okapi\Aop\Tests\Functional\ClassHierarchyAspect\ClassesToIntercept\SmsSender;

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
