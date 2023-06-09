<?php

namespace Okapi\Aop\Tests\Functional\AbstractMethod\ClassesToIntercept;

class LocalFileUploader extends FileUploader
{
    public function upload(string $filePath): string
    {
        return $filePath;
    }
}
