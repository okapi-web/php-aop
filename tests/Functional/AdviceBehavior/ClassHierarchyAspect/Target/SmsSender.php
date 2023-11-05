<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ClassHierarchyAspect\Target;

class SmsSender implements SmsSenderInterface
{
    public function send(string $recipient, string $message): bool
    {
        // Logic to send an SMS

        return true;
    }
}
