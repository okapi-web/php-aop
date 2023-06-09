<?php

namespace Okapi\Aop\Tests\Functional\ClassHierarchyAspect\ClassesToIntercept;

interface EmailSenderInterface
{
    public function send(string $recipient, string $subject, string $body): bool;
}
