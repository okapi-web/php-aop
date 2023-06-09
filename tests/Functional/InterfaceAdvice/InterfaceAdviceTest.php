<?php

namespace Okapi\Aop\Tests\Functional\InterfaceAdvice;

use Okapi\Aop\Tests\Functional\InterfaceAdvice\Aspect\UserInterfaceAspect;
use Okapi\Aop\Tests\Functional\InterfaceAdvice\ClassesToIntercept\User;
use Okapi\Aop\Tests\Stubs\Kernel\ApplicationKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class InterfaceAdviceTest extends TestCase
{
    /**
     * @see UserInterfaceAspect::modifyName()
     */
    public function testInterfaceAdvice(): void
    {
        Util::clearCache();
        ApplicationKernel::init();

        $user     = new User();
        $userName = $user->getName();

        $this->assertSame('Jane Doe', $userName);
    }
}
