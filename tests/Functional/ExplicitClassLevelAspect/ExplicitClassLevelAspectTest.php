<?php

namespace Okapi\Aop\Tests\Functional\ExplicitClassLevelAspect;

use Okapi\Aop\Tests\Functional\ExplicitClassLevelAspect\Aspect\LoggingAspect;
use Okapi\Aop\Tests\Functional\ExplicitClassLevelAspect\ClassesToMatch\InventoryTracker;
use Okapi\Aop\Tests\Stubs\Etc\Logger;
use Okapi\Aop\Tests\Stubs\Kernel\EmptyKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class ExplicitClassLevelAspectTest extends TestCase
{
    public function testExplicitClassLevelAspect(): void
    {
        Util::clearCache();
        EmptyKernel::init();

        $this->executeTest();
    }

    public function testCachedExplicitClassLevelAspect(): void
    {
        EmptyKernel::init();

        $this->executeTest();
    }

    /**
     * @see LoggingAspect::logAllMethods()
     * @see LoggingAspect::logUpdateInventory()
     */
    private function executeTest(): void
    {
        $inventoryTracker = new InventoryTracker();
        $inventoryTracker->updateInventory(1, 100);
        $inventoryTracker->updateInventory(2, 200);

        $this->assertEquals(100, $inventoryTracker->checkInventory(1));
        $this->assertEquals(200, $inventoryTracker->checkInventory(2));

        $logger = Logger::getInstance();

        $logs  = $logger->getLogs();
        $this->assertCount(6, $logs);

        $updateInventoryExecuted = 0;
        $checkInventoryExecuted = 0;
        $updateInventoryLog = "Method 'updateInventory' executed.";
        $checkInventoryLog = "Method 'checkInventory' executed.";

        foreach ($logs as $log) {
            if ($log === $updateInventoryLog) {
                $updateInventoryExecuted++;
            } elseif ($log === $checkInventoryLog) {
                $checkInventoryExecuted++;
            }
        }

        $this->assertEquals(4, $updateInventoryExecuted);
        $this->assertEquals(2, $checkInventoryExecuted);
    }
}
