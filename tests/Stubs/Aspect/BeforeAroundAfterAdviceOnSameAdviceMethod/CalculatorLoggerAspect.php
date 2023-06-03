<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Stubs\Aspect\BeforeAroundAfterAdviceOnSameAdviceMethod;

use Okapi\Aop\Advice\AdviceType;
use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Attributes\Before;
use Okapi\Aop\Invocation\AroundMethodInvocation;
use Okapi\Aop\Invocation\MethodInvocation;
use Okapi\Aop\Tests\Stubs\ClassesToIntercept\BeforeAroundAfterAdviceOnSameAdviceMethod\Calculator;
use Okapi\Aop\Tests\Stubs\Etc\Logger;

#[Aspect]
class CalculatorLoggerAspect
{
    #[Before(
        class: Calculator::class,
        method: 'add',
    )]
    #[Around(
        class: Calculator::class,
        method: 'add',
    )]
    #[After(
        class: Calculator::class,
        method: 'add',
    )]
    public function logCalculation(MethodInvocation $invocation): void
    {
        $adviceType = $invocation->getAdviceType();
        $logger = Logger::getInstance();

        if ($adviceType === AdviceType::Before) {
            $message = 'Starting calculation...';
            $logger->log($message);
        }

        if ($adviceType === AdviceType::Around) {
            assert($invocation instanceof AroundMethodInvocation);

            $startTime = microtime(true);
            $invocation->proceed();
            $endTime = microtime(true);
            $elapsedTime = $endTime - $startTime;

            $message = sprintf('Calculation took %.2f seconds', $elapsedTime);
            $logger->log($message);
        }

        if ($adviceType === AdviceType::After) {
            $result = $invocation->proceed();

            $message = sprintf('Calculation result: %d', $result);
            $logger->log($message);
        }
    }
}
