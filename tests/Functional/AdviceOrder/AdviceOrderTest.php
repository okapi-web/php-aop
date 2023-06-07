<?php

namespace Okapi\Aop\Tests\Functional\AdviceOrder;

use Okapi\Aop\Tests\Functional\AdviceOrder\Aspect\ArticleModerationAspect;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;
use Okapi\Aop\Tests\Stubs\Kernel\ApplicationKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class AdviceOrderTest extends TestCase
{
    /**
     * @see ArticleModerationAspect::validateContent()
     * @see ArticleModerationAspect::checkForSpam()
     * @see ArticleModerationAspect::ensureProperFormatting()
     */
    public function testAdviceOrderTest(): void
    {
        Util::clearCache();
        ApplicationKernel::init();

        $articleManager = new ClassesToIntercept\ArticleManager();

        $articleManager->createArticle(
            'Hello World',
            'AOP is awesome!',
        );

        $stackTrace = StackTrace::getInstance();

        $this->assertEquals(
            [
                'checkForSpam',
                'validateContent',
                'ensureProperFormatting',
            ],
            $stackTrace->getStackTrace(),
        );
    }
}
