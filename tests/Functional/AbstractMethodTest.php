<?php

namespace Okapi\Aop\Tests\Functional;

use Okapi\Aop\Tests\Stubs\Aspect\AbstractMethod\FileUploaderAspect;
use Okapi\Aop\Tests\Stubs\ClassesToIntercept\AbstractMethod\LocalFileUploader;
use Okapi\Aop\Tests\Stubs\Kernel\ApplicationKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class AbstractMethodTest extends TestCase
{
    /**
     * @see FileUploaderAspect::modifyResult()
     */
    public function testAbstractMethod(): void
    {
        Util::clearCache();
        ApplicationKernel::init();

        $uploader = new LocalFileUploader();

        $result = $uploader->upload('C:\Windows\Temp\file.txt');

        $this->assertEquals(
            'C:/Windows/Temp/file.txt',
            $result
        );
    }
}
