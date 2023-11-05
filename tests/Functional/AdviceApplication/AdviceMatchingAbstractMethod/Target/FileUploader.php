<?php

namespace Okapi\Aop\Tests\Functional\AdviceApplication\AdviceMatchingAbstractMethod\Target;

abstract class FileUploader
{
    abstract public function upload(string $filePath): string;
}
