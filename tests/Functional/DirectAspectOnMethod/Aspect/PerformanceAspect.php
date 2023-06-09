<?php

namespace Okapi\Aop\Tests\Functional\DirectAspectOnMethod\Aspect;

use Attribute;
use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AroundMethodInvocation;
use Okapi\Aop\Tests\Stubs\Etc\Logger;

#[Attribute]
#[Aspect]
class PerformanceAspect
{
    #[Around]
    public function measure(AroundMethodInvocation $invocation): void
    {
        $start = microtime(true);
        $invocation->proceed();
        $end = microtime(true);

        $executionTime = $end - $start;

        $class  = $invocation->getClassName();
        $method = $invocation->getMethodName();

        $logMessage = sprintf(
            "Method %s::%s executed in %.2f seconds.",
            $class,
            $method,
            $executionTime,
        );

        $logger = Logger::getInstance();
        $logger->log($logMessage);
    }
}
