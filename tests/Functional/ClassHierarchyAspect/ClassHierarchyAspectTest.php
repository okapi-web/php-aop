<?php

namespace Okapi\Aop\Tests\Functional\ClassHierarchyAspect;

use Okapi\Aop\Tests\Functional\ClassHierarchyAspect\Aspect\NotificationAspect;
use Okapi\Aop\Tests\Functional\ClassHierarchyAspect\ClassesToIntercept\EmailSender;
use Okapi\Aop\Tests\Functional\ClassHierarchyAspect\ClassesToIntercept\SmsSender;
use Okapi\Aop\Tests\Stubs\Kernel\ApplicationKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class ClassHierarchyAspectTest extends TestCase
{
    /**
     * @see NotificationAspect::verifyNotSms()
     */
    public function testClassHierarchyAspect(): void
    {
        Util::clearCache();
        ApplicationKernel::init();

        $emailSender = new EmailSender();

        $recipient = 'test@test.com';
        $subject   = 'Test';
        $body      = 'Test';
        $result    = $emailSender->send($recipient, $subject, $body);

        $this->assertTrue($result);


        $smsSender = new SmsSender();

        $recipient = '123456789';
        $message   = 'Test';
        $result    = $smsSender->send($recipient, $message);

        // Should not throw an error
        $this->assertTrue($result);
    }
}
