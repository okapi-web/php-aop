<?php

namespace Okapi\Aop\Tests\Functional\Advice\AdviceOnlyPublic;

use Okapi\Aop\Tests\Stubs\Etc\StackTrace;
use Okapi\Aop\Tests\Stubs\Kernel\ApplicationKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class AdviceOnlyPublicTest extends TestCase
{
    /**
     * @see ArticleModerationAspect::validateContent()
     * @see ArticleModerationAspect::checkForSpam()
     * @see ArticleModerationAspect::ensureProperFormatting()
     */
    public function testAdviceOnlyPublic(): void
    {
        Util::clearCache();
        TargetKernel::init();

        $targetClass = new TargetClass();

        $targetClass->helloWorld();
        $targetClass->helloHere();

        $stackTrace = StackTrace::getInstance();
        $this->assertEquals(
            [
                'AspectDefault',
                'AspectOnlyPublic',
                'AspectDefault',
            ],
            $stackTrace->getStackTrace(),
        );
    }
}
