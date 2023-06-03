<?php

namespace Okapi\Aop\Tests\Functional;

use Exception;
use Okapi\Aop\Tests\Stubs\Aspect\ExceptionInsideAdvice\CommentFilterAspect;
use Okapi\Aop\Tests\Stubs\ClassesToIntercept\ExceptionInsideAdvice\CommentController;
use Okapi\Aop\Tests\Stubs\Kernel\ApplicationKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class ExceptionInsideAdviceTest extends TestCase
{
    /**
     * @see CommentFilterAspect::checkForInappropriateLanguage()
     */
    public function testExceptionInsideAdvice(): void
    {
        Util::clearCache();
        ApplicationKernel::init();

        $commentController = new CommentController();

        $commentController->saveComment('This is a good comment');
        $this->assertTrue(true);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Comment contains inappropriate language!');
        $commentController->saveComment('This is a bad comment');
    }
}
