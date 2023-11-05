<?php

namespace Okapi\Aop\Tests\Functional\AdviceApplication\ExplicitMethodLevelAspect;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceApplication\ExplicitMethodLevelAspect\Aspect\PerformanceAspect;
use Okapi\Aop\Tests\Functional\AdviceApplication\ExplicitMethodLevelAspect\Target\CustomerService;
use Okapi\Aop\Tests\Stubs\Etc\Logger;
use Okapi\Aop\Tests\Stubs\Kernel\EmptyKernel;
use Okapi\Aop\Tests\Util;
use Okapi\Wildcards\Regex;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class ExplicitMethodLevelAspectTest extends TestCase
{
    use ClassLoaderMockTrait;

    public function testNotRegisteredExplicitMethodLevelAspect(): void
    {
        Util::clearCache();
        EmptyKernel::init();

        $this->assertWillBeWoven(CustomerService::class);

        $this->executeTest();
    }

    public function testCachedNotRegisteredExplicitMethodLevelAspect(): void
    {
        EmptyKernel::init();

        $this->assertAspectLoadedFromCache(CustomerService::class);

        $this->executeTest();
    }

    public function testRegisteredExplicitMethodLevelAspect(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(CustomerService::class);

        $this->executeTest();
    }

    public function testCachedExplicitMethodLevelAspect(): void
    {
        Kernel::init();

        $this->assertAspectLoadedFromCache(CustomerService::class);

        $this->executeTest();
    }

    /**
     * @see PerformanceAspect::measure()
     */
    private function executeTest(): void
    {
        $customerService = new CustomerService();
        $customerService->createCustomer();

        $logger = Logger::getInstance();

        $logs = $logger->getLogs();
        $this->assertCount(1, $logs);

        $firstLog = $logs[0];
        $wildcard = 'Method *::* executed in * seconds.';
        $regex    = Regex::fromWildcard($wildcard);
        $matches  = $regex->matches($firstLog);
        $this->assertTrue($matches);
    }
}
