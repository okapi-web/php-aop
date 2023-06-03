<?php

namespace Okapi\Aop\Tests\Stubs\ClassesToIntercept\AbstractMethod;

class LocalFileUploader extends FileUploader
{
    public function upload(string $filePath): string
    {
        return $filePath;
    }
}
