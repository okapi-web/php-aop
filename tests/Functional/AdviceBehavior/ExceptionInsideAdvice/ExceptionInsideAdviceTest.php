<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ExceptionInsideAdvice;

use Exception;
use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceBehavior\ExceptionInsideAdvice\Aspect\CommentFilterAspect;
use Okapi\Aop\Tests\Functional\AdviceBehavior\ExceptionInsideAdvice\Target\CommentController;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class ExceptionInsideAdviceTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see CommentFilterAspect::checkForInappropriateLanguage()
     */
    public function testExceptionInsideAdvice(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(CommentController::class);
        $commentController = new CommentController();

        $commentController->saveComment('This is a good comment');
        $this->assertTrue(true);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Comment contains inappropriate language!');
        $commentController->saveComment('This is a bad comment');
    }
}
