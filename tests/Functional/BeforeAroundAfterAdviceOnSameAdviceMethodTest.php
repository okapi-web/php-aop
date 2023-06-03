<?php

namespace Okapi\Aop\Tests\Functional;

use Okapi\Aop\Tests\Stubs\Aspect\BeforeAroundAfterAdviceOnSameAdviceMethod\CalculatorLoggerAspect;
use Okapi\Aop\Tests\Stubs\ClassesToIntercept\BeforeAroundAfterAdviceOnSameAdviceMethod\Calculator;
use Okapi\Aop\Tests\Stubs\Etc\Logger;
use Okapi\Aop\Tests\Stubs\Kernel\ApplicationKernel;
use Okapi\Aop\Tests\Util;
use Okapi\Wildcards\Regex;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class BeforeAroundAfterAdviceOnSameAdviceMethodTest extends TestCase
{
    /**
     * @see CalculatorLoggerAspect::logCalculation()
     */
    public function testBeforeAroundAfterAdviceOnSameAdviceMethod(): void
    {
        Util::clearCache();
        ApplicationKernel::init();

        $calculator = new Calculator();

        $result = $calculator->add(2, 3);
        $this->assertSame(5, $result);

        $logger = Logger::getInstance();

        $logs = $logger->getLogs();
        $this->assertCount(3, $logs);

        $log1 = $logs[0];
        $this->assertSame('Starting calculation...', $log1);

        $log2 = $logs[1];
        $wildcard = 'Calculation took * seconds';
        $regex = Regex::fromWildcard($wildcard);
        $matches = $regex->matches($log2);
        $this->assertTrue($matches);

        $log3 = $logs[2];
        $this->assertSame('Calculation result: 5', $log3);
    }
}
