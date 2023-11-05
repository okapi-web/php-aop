<?php

namespace Okapi\Aop\Tests\Functional\AspectMatching\ClassHierarchyOnlyInvokedOnce;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AspectMatching\ClassHierarchyOnlyInvokedOnce\Target\TargetClassA;
use Okapi\Aop\Tests\Functional\AspectMatching\ClassHierarchyOnlyInvokedOnce\Target\TargetClassC;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class ClassHierarchyOnlyInvokedOnceTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see Aspect::logMethodCalls()
     */
    public function testAdviceIsInvokedOnlyOnce(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(TargetClassC::class);
        $instance = new TargetClassA();
        $instance->helloWorld();
        $instance->helloWorld();

        $stackTrace = StackTrace::getInstance();

        $this->assertEquals(
            [
                'Method call 1',
                'Method call 2',
            ],
            $stackTrace->getStackTrace(),
        );
    }
}
