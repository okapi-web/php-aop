<?php

/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\AdviceBehavior\BeforeAroundAfterAdviceOnSameAdviceMethod\Aspect;

use Okapi\Aop\Advice\AdviceType;
use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Attributes\Before;
use Okapi\Aop\Invocation\AroundMethodInvocation;
use Okapi\Aop\Invocation\MethodInvocation;
use Okapi\Aop\Tests\Functional\AdviceBehavior\BeforeAroundAfterAdviceOnSameAdviceMethod\Target\Calculator;
use Okapi\Aop\Tests\Stubs\Etc\Logger;
use PHPUnit\Framework\Assert;

#[Aspect]
class CalculatorLoggerAspect
{
    #[Before(class: Calculator::class, method: 'add')]
    #[Around(class: Calculator::class, method: 'add')]
    #[After(class: Calculator::class, method: 'add')]
    public function logCalculation(MethodInvocation $invocation): void
    {
        $adviceType = $invocation->getAdviceType();
        $logger = Logger::getInstance();

        if ($adviceType === AdviceType::Before) {
            $message = 'Starting calculation...';
            $logger->log($message);
        }

        if ($adviceType === AdviceType::Around) {
            Assert::assertInstanceOf(
                AroundMethodInvocation::class,
                $invocation,
                'The invocation must match the configured test fixture.',
            );

            $startTime = microtime(true);
            $invocation->proceed();
            $endTime = microtime(true);
            $elapsedTime = $endTime - $startTime;

            $message = sprintf('Calculation took %.2f seconds', $elapsedTime);
            $logger->log($message);
        }

        if ($adviceType === AdviceType::After) {
            Assert::assertInstanceOf(
                \Okapi\Aop\Invocation\AfterMethodInvocation::class,
                $invocation,
                'The invocation must match the configured test fixture.',
            );
            /** @var int $result */
            $result = $invocation->proceed();

            $message = sprintf('Calculation result: %d', $result);
            $logger->log($message);
        }
    }
}
