<?php

namespace Okapi\Aop\Tests\Functional\AspectMatching\InterceptTraitMethods;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AspectMatching\InterceptTraitMethods\Aspect\DefaultAspect;
use Okapi\Aop\Tests\Functional\AspectMatching\InterceptTraitMethods\Aspect\InterceptTraitMethodsAspect;
use Okapi\Aop\Tests\Functional\AspectMatching\InterceptTraitMethods\Target\TargetClass;
use Okapi\Aop\Tests\Functional\AspectMatching\InterceptTraitMethods\Target\TargetTrait;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class AdviceInterceptTraitMethodsTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see DefaultAspect::validateContent()
     * @see InterceptTraitMethodsAspect::validateContent()
     */
    public function testTraitMethodsNotWoven(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(TargetClass::class);
        $this->assertAspectNotApplied(TargetTrait::class);
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
