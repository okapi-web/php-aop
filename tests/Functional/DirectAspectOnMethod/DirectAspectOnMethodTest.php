<?php

namespace Okapi\Aop\Tests\Functional\DirectAspectOnMethod;

use Okapi\Aop\Tests\Functional\DirectAspectOnMethod\Aspect\PerformanceAspect;
use Okapi\Aop\Tests\Functional\DirectAspectOnMethod\ClassesToIntercept\CustomerService;
use Okapi\Aop\Tests\Stubs\Etc\Logger;
use Okapi\Aop\Tests\Stubs\Kernel\ApplicationKernel;
use Okapi\Aop\Tests\Util;
use Okapi\Wildcards\Regex;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class DirectAspectOnMethodTest extends TestCase
{
    /**
     * @see PerformanceAspect::measure()
     */
    public function testMethodAdvice(): void
    {
        Util::clearCache();
        ApplicationKernel::init();

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
