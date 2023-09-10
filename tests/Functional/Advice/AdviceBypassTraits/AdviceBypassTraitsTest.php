<?php

namespace Okapi\Aop\Tests\Functional\Advice\AdviceBypassTraits;

use Okapi\Aop\Tests\Stubs\Etc\StackTrace;
use Okapi\Aop\Tests\Stubs\Kernel\ApplicationKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class AdviceBypassTraitsTest extends TestCase
{
    /**
     * @see ArticleModerationAspect::validateContent()
     * @see ArticleModerationAspect::checkForSpam()
     * @see ArticleModerationAspect::ensureProperFormatting()
     */
    public function testAdviceBypassTraits(): void
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
                'AspectBypassTraits',
                'AspectDefault',
            ],
            $stackTrace->getStackTrace(),
        );
    }
}
