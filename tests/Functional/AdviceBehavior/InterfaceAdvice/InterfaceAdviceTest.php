<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\InterfaceAdvice;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceBehavior\InterfaceAdvice\Aspect\UserInterfaceAspect;
use Okapi\Aop\Tests\Functional\AdviceBehavior\InterfaceAdvice\Target\User;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class InterfaceAdviceTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see UserInterfaceAspect::modifyName()
     */
    public function testInterfaceAdvice(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(User::class);
        $user     = new User();
        $userName = $user->getName();

        $this->assertSame('Jane Doe', $userName);
    }
}
