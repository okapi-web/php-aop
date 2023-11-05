<?php

namespace Okapi\Aop\Tests\Functional\AdviceApplication\AdviceMatchingAbstractMethod\Target;

class LocalFileUploader extends FileUploader
{
    public function upload(string $filePath): string
    {
        return $filePath;
    }
}
