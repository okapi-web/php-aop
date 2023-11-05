<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ClassHierarchyAspect\Target;

interface EmailSenderInterface
{
    public function send(string $recipient, string $subject, string $body): bool;
}
