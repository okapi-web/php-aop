<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\BeforeAroundAfterAdviceOnSameAdviceMethod;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceBehavior\BeforeAroundAfterAdviceOnSameAdviceMethod\Aspect\CalculatorLoggerAspect;
use Okapi\Aop\Tests\Functional\AdviceBehavior\BeforeAroundAfterAdviceOnSameAdviceMethod\Target\Calculator;
use Okapi\Aop\Tests\Stubs\Etc\Logger;
use Okapi\Aop\Tests\Util;
use Okapi\Wildcards\Regex;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class BeforeAroundAfterAdviceOnSameAdviceMethodTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see CalculatorLoggerAspect::logCalculation()
     */
    public function testBeforeAroundAfterAdviceOnSameAdviceMethod(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(Calculator::class);
        $calculator = new Calculator();

        $result = $calculator->add(2, 3);
        $this->assertSame(5, $result);

        $logger = Logger::getInstance();

        $logs = $logger->getLogs();
        $this->assertCount(3, $logs);

        $log1 = $logs[0];
        $this->assertSame('Starting calculation...', $log1);

        $log2     = $logs[1];
        $wildcard = 'Calculation took * seconds';
        $regex    = Regex::fromWildcard($wildcard);
        $matches  = $regex->matches($log2);
        $this->assertTrue($matches);

        $log3 = $logs[2];
        $this->assertSame('Calculation result: 5', $log3);
    }
}
