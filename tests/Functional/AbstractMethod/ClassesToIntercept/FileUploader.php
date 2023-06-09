<?php

namespace Okapi\Aop\Tests\Functional\AbstractMethod\ClassesToIntercept;

abstract class FileUploader
{
    abstract public function upload(string $filePath): string;
}
