<?php

namespace Okapi\Aop\Tests\Stubs\ClassesToIntercept\AbstractMethod;

abstract class FileUploader
{
    abstract public function upload(string $filePath): string;
}
