<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\ErrorHandling\MissingClassOrMethod\Aspect;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Tests\Functional\ErrorHandling\MissingClassOrMethod\Target\InventoryManager;
use Okapi\Aop\Tests\Stubs\Etc\Logger;

#[Aspect]
class RemoveItemLoggerAspect
{
    #[After(
        class: InventoryManager::class,
    )]
    public function logRemoveItem(AfterMethodInvocation $invocation): void
    {
        $itemName = $invocation->getArgument('itemName');

        $logMessage = sprintf(
            "Item %s removed from inventory.",
            $itemName,
        );

        $logger = Logger::getInstance();
        $logger->log($logMessage);
    }
}
