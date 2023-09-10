<?php

namespace Okapi\Aop\Tests\Functional\ClassHierarchyOnlyInvokedOnce;

use Okapi\Aop\Tests\Functional\ClassHierarchyOnlyInvokedOnce\Aspect\ClassHierarchyOnlyInvokedOnceAspect;
use Okapi\Aop\Tests\Functional\ClassHierarchyOnlyInvokedOnce\Target\TargetClassA;
use Okapi\Aop\Tests\Functional\ClassHierarchyOnlyInvokedOnce\Kernel\ClassHierarchyOnlyInvokedOnceKernel;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class ClassHierarchyOnlyInvokedOnceTest extends TestCase
{
    /**
     * @see ClassHierarchyOnlyInvokedOnceAspect::logMethodCalls()
     */
    public function testAdviceIsInvokedOnlyOnce(): void
    {
        Util::clearCache();
        ClassHierarchyOnlyInvokedOnceKernel::init();

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
