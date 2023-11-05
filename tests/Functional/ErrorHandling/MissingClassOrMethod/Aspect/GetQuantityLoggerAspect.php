<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\ErrorHandling\MissingClassOrMethod\Aspect;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Tests\Stubs\Etc\Logger;

#[Aspect]
class GetQuantityLoggerAspect
{
    #[After]
    public function logGetQuantity(AfterMethodInvocation $invocation): void
    {
        $itemName = $invocation->getArgument('itemName');
        $quantity = $invocation->proceed();

        $logMessage = sprintf(
            "Item %s has quantity %d.",
            $itemName,
            $quantity,
        );

        $logger = Logger::getInstance();
        $logger->log($logMessage);
    }
}
