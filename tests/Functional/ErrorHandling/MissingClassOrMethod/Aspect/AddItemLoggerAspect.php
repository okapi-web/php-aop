<?php

namespace Okapi\Aop\Tests\Functional\ErrorHandling\MissingClassOrMethod\Aspect;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Tests\Stubs\Etc\Logger;

#[Aspect]
class AddItemLoggerAspect
{
    #[After(
        method: 'addItem',
    )]
    public function logAddItem(AfterMethodInvocation $invocation): void
    {
        $itemName = $invocation->getArgument('itemName');
        $quantity = $invocation->getArgument('quantity');

        $logMessage = sprintf(
            "Item %s added to inventory with quantity %d.",
            $itemName,
            $quantity,
        );

        $logger = Logger::getInstance();
        $logger->log($logMessage);
    }
}
