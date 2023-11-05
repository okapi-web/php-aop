<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\OnlyPublicMethods;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceBehavior\OnlyPublicMethods\Aspect\DefaultAspect;
use Okapi\Aop\Tests\Functional\AdviceBehavior\OnlyPublicMethods\Aspect\OnlyPublicMethodsAspect;
use Okapi\Aop\Tests\Functional\AdviceBehavior\OnlyPublicMethods\Target\TargetClass;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class OnlyPublicMethodsTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see DefaultAspect::validateContent()
     * @see OnlyPublicMethodsAspect::validateContent()
     */
    public function testOnlyPublicMethodsAreWoven(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(TargetClass::class);
        $targetClass = new TargetClass();

        $targetClass->helloWorld();
        $targetClass->parentHelloWorld();
        $targetClass->askParentHelloHere();
        $targetClass->traitHelloWorld();
        $targetClass->askTraitHelloHere();

        $stackTrace = StackTrace::getInstance();
        $this->assertEquals(
            [
                // Call to $targetClass->helloWorld() = 2 Advice invocations
                'DefaultAspect helloWorld',
                'OnlyPublicMethodsAspect helloWorld',
                // Call to $targetClass->parentHelloWorld() = 2 Advice invocations
                'DefaultAspect parentHelloWorld',
                'OnlyPublicMethodsAspect parentHelloWorld',
                // Call to $targetClass->askParentHelloHere() = 3 Advice invocations
                'DefaultAspect parentHelloHere',
                'DefaultAspect askParentHelloHere',
                'OnlyPublicMethodsAspect askParentHelloHere',
                // Call to $targetClass->traitHelloWorld() = 2 Advice invocations
                'DefaultAspect traitHelloWorld',
                'OnlyPublicMethodsAspect traitHelloWorld',
                // Call to $targetClass->askTraitHelloHere() = 3 Advice invocations
                'DefaultAspect traitHelloHere',
                'DefaultAspect askTraitHelloHere',
                'OnlyPublicMethodsAspect askTraitHelloHere',
            ],
            $stackTrace->getStackTrace(),
        );
    }
}
