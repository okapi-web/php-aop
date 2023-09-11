<?php

namespace Okapi\Aop\Tests\Functional\AdviceOptions\AdviceBypassTraitMethods;

use Okapi\Aop\Tests\Stubs\Etc\StackTrace;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class AdviceBypassTraitMethodsTest extends TestCase
{

    public function testTraitMethodsNotWoven()
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
                'AspectDefault',
                'AspectBypassTraitMethods',
                // Second call to TargetTrait::helloHere()
                'AspectDefault',
            ],
            $stackTrace->getStackTrace(),
        );

    }
}
