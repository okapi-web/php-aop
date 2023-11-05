<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\AdviceOrder;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceBehavior\AdviceOrder\Aspect\ArticleModerationAspect;
use Okapi\Aop\Tests\Functional\AdviceBehavior\AdviceOrder\Target\ArticleManager;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class AdviceOrderTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see ArticleModerationAspect::validateContent()
     * @see ArticleModerationAspect::checkForSpam()
     * @see ArticleModerationAspect::ensureProperFormatting()
     */
    public function testAdviceOrderTest(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(ArticleManager::class);
        $articleManager = new ArticleManager();

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
