<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\ExplicitClassLevelAspect\Aspect;

use Attribute;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Attributes\Before;
use Okapi\Aop\Invocation\BeforeMethodInvocation;
use Okapi\Aop\Tests\Stubs\Etc\Logger;

#[Attribute]
#[Aspect]
class LoggingAspect
{
    #[Before]
    public function logAllMethods(BeforeMethodInvocation $invocation): void
    {
        $methodName = $invocation->getMethodName();

        $logMessage = sprintf(
            "Method '%s' executed.",
            $methodName,
        );

        $logger = Logger::getInstance();
        $logger->log($logMessage);
    }

    #[Before(
        method: 'updateInventory',
    )]
    public function logUpdateInventory(BeforeMethodInvocation $invocation): void
    {
        $methodName = $invocation->getMethodName();

        $logMessage = sprintf(
            "Method '%s' executed.",
            $methodName,
        );

        $logger = Logger::getInstance();
        $logger->log($logMessage);
    }
}
