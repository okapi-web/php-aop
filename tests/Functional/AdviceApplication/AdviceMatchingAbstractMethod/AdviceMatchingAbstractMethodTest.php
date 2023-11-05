<?php

namespace Okapi\Aop\Tests\Functional\AdviceApplication\AdviceMatchingAbstractMethod;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceApplication\AdviceMatchingAbstractMethod\Aspect\FileUploaderAspect;
use Okapi\Aop\Tests\Functional\AdviceApplication\AdviceMatchingAbstractMethod\Target\LocalFileUploader;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class AdviceMatchingAbstractMethodTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see FileUploaderAspect::modifyResult()
     */
    public function testAbstractMethod(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(LocalFileUploader::class);

        $uploader = new LocalFileUploader();

        $result = $uploader->upload('C:\Windows\Temp\file.txt');

        /** @noinspection PhpConditionAlreadyCheckedInspection */
        $this->assertEquals(
            'C:/Windows/Temp/file.txt',
            $result
        );
    }
}
