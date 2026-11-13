<?php

namespace Okapi\Aop\Invocation;

use Throwable;

class AfterThrowingMethodInvocation extends MethodInvocation
{
    /**
     * @param array<array-key, mixed> $arguments
     */
    public function __construct(
        ?object $subject,
        string $className,
        string $methodName,
        array &$arguments,
        private readonly Throwable $throwable,
    ) {
        parent::__construct(
            subject: $subject,
            className: $className,
            methodName: $methodName,
            result: null,
            arguments: $arguments,
        );
    }

    public function getThrowable(): Throwable
    {
        return $this->throwable;
    }
}
