<?php

namespace Okapi\Aop\Tests\Functional\ClassHierarchyAspect\ClassesToIntercept;

interface SmsSenderInterface
{
    public function send(string $recipient, string $message): bool;
}
