<?php

namespace Okapi\Aop\Tests\Functional\ClassHierarchyAspect\ClassesToIntercept;

class EmailSender implements EmailSenderInterface
{
    public function send(string $recipient, string $subject, string $body): bool
    {
        // Logic to send an email

        return true;
    }
}
