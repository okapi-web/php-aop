<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ClassHierarchyAspect;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceBehavior\ClassHierarchyAspect\Aspect\NotificationAspect;
use Okapi\Aop\Tests\Functional\AdviceBehavior\ClassHierarchyAspect\Target\EmailSender;
use Okapi\Aop\Tests\Functional\AdviceBehavior\ClassHierarchyAspect\Target\SmsSender;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class ClassHierarchyAspectTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see NotificationAspect::verifyNotSms()
     */
    public function testClassHierarchyAspect(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(EmailSender::class);
        $emailSender = new EmailSender();

        $recipient = 'test@test.com';
        $subject   = 'Test';
        $body      = 'Test';
        $result    = $emailSender->send($recipient, $subject, $body);

        $this->assertTrue($result);


        $this->assertAspectNotApplied(SmsSender::class);
        $smsSender = new SmsSender();

        $recipient = '123456789';
        $message   = 'Test';
        $result    = $smsSender->send($recipient, $message);

        // Should not throw an error
        $this->assertTrue($result);
    }
}
