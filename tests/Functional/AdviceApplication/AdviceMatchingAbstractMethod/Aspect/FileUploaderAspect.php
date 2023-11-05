<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\AdviceApplication\AdviceMatchingAbstractMethod\Aspect;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Tests\Functional\AdviceApplication\AdviceMatchingAbstractMethod\Target\FileUploader;

#[Aspect]
class FileUploaderAspect
{
    #[After(
        class: FileUploader::class,
        method: 'upload',
    )]
    public function modifyResult(AfterMethodInvocation $invocation): void
    {
        $result = $invocation->proceed();
        $modifiedResult = str_replace('\\', '/', $result);
        $invocation->setResult($modifiedResult);
    }
}
