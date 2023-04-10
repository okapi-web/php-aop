<?php

namespace Okapi\Aop\Tests\Functional\Workflow;

use Okapi\Aop\Tests\Stubs\ClassesToIntercept;
use Okapi\Aop\Tests\Stubs\Kernel\ApplicationKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunClassInSeparateProcess;
use PHPUnit\Framework\TestCase;

#[RunClassInSeparateProcess]
class ApplicationTest extends TestCase
{
    public function testKernel(): void
    {
        Util::clearCache();

        $this->assertFalse(ApplicationKernel::isInitialized());
        ApplicationKernel::init();
        $this->assertTrue(ApplicationKernel::isInitialized());

        $this->assertFileDoesNotExist(Util::CACHE_STATES_FILE);
    }

    public function testHelloWorldClass(): void
    {
        $helloWorldClass = new ClassesToIntercept\HelloWorldClass();
        $this->assertSame('HELLO WORLD!', $helloWorldClass->test());
    }
}
