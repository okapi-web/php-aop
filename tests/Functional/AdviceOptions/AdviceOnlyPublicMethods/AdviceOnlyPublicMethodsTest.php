<?php
declare(strict_types=1);

namespace Okapi\Aop\Tests\Functional\AdviceOptions\AdviceOnlyPublicMethods;

use Okapi\Aop\Tests\Stubs\Etc\StackTrace;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class AdviceOnlyPublicMethodsTest extends TestCase
{
    public function testOnlyPublicMethodsAreWoven()
    {
        Util::clearCache();
        Kernel::init();

        $targetClass = new TargetClass();

        $targetClass->helloWorld();
        $targetClass->parentHelloWorld();
        $targetClass->askParentHelloHere();
        $targetClass->traitHelloWorld();
        $targetClass->askTraitHelloHere();

        $stackTrace = StackTrace::getInstance();
        $this->assertEquals(
            [
                // Call to $targetClass->helloWorld() = 2 Advice invoations
                'AspectDefault helloWorld',
                'AspectOnlyPublicMethods helloWorld',
                // Call to $targetClass->parentHelloWorld() = 2 Advice invoations
                'AspectDefault parentHelloWorld',
                'AspectOnlyPublicMethods parentHelloWorld',
                // Call to $targetClass->askParentHelloHere() = 3 Advice invoations
                'AspectDefault parentHelloHere',
                'AspectDefault askParentHelloHere',
                'AspectOnlyPublicMethods askParentHelloHere',
                // Call to $targetClass->traitHelloWorld() = 2 Advice invoations
                'AspectDefault traitHelloWorld',
                'AspectOnlyPublicMethods traitHelloWorld',
                // Call to $targetClass->askTraitHelloHere() = 3 Advice invoations
                'AspectDefault traitHelloHere',
                'AspectDefault askTraitHelloHere',
                'AspectOnlyPublicMethods askTraitHelloHere',
            ],
            $stackTrace->getStackTrace(),
        );
    }
}
