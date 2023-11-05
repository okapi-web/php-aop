<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ClassHierarchyAspect\Target;

interface SmsSenderInterface
{
    public function send(string $recipient, string $message): bool;
}
