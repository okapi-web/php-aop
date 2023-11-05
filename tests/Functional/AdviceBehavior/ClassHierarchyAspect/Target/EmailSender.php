<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ClassHierarchyAspect\Target;

class EmailSender implements EmailSenderInterface
{
    public function send(string $recipient, string $subject, string $body): bool
    {
        // Logic to send an email

        return true;
    }
}
