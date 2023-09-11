<?php

namespace Okapi\Aop\Tests\Functional\AdviceOptions\InterceptTraitMethods;

use Okapi\Aop\Tests\Functional\AdviceOptions\InterceptTraitMethods\Kernel\Kernel;
use Okapi\Aop\Tests\Functional\AdviceOptions\InterceptTraitMethods\Target\TargetClass;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class AdviceInterceptTraitMethodsTest extends TestCase
{
    public function testTraitMethodsNotWoven(): void
    {
        Util::clearCache();
        Kernel::init();

        $targetClass = new TargetClass();

        $targetClass->helloWorld();
        $targetClass->helloHere();

        $stackTrace = StackTrace::getInstance();
        $this->assertEquals(
            [
                // First call to TargetClass::helloWorld()
                'DefaultAspect',
                'InterceptTraitMethodsAspect',
                // Second call to TargetTrait::helloHere()
                'DefaultAspect',
            ],
            $stackTrace->getStackTrace(),
        );
    }
}
