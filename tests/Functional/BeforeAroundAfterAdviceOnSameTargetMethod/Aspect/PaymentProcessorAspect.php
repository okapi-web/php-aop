<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\BeforeAroundAfterAdviceOnSameTargetMethod\Aspect;

use InvalidArgumentException;
use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Attributes\Before;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Invocation\AroundMethodInvocation;
use Okapi\Aop\Invocation\BeforeMethodInvocation;
use Okapi\Aop\Tests\Functional\BeforeAroundAfterAdviceOnSameTargetMethod\ClassesToIntercept\PaymentProcessor;
use Okapi\Aop\Tests\Stubs\Etc\Logger;
use Okapi\Aop\Tests\Stubs\Etc\MailQueue;

#[Aspect]
class PaymentProcessorAspect
{
    #[Before(
        class: PaymentProcessor::class,
        method: 'processPayment',
    )]
    public function checkPaymentAmount(BeforeMethodInvocation $invocation): void
    {
        $amount = $invocation->getArgument('amount');

        if ($amount < 0) {
            throw new InvalidArgumentException('Invalid payment amount');
        }
    }

    #[Around(
        class: PaymentProcessor::class,
        method: 'processPayment',
    )]
    public function logPayment(AroundMethodInvocation $invocation): void
    {
        $startTime = microtime(true);

        $invocation->proceed();

        $endTime     = microtime(true);
        $elapsedTime = $endTime - $startTime;

        $amount = $invocation->getArgument('amount');

        $logMessage = sprintf(
            'Payment processed for amount $%.2f in %.2f seconds',
            $amount,
            $elapsedTime,
        );

        $logger = Logger::getInstance();
        $logger->log($logMessage);
    }

    #[After(
        class: PaymentProcessor::class,
        method: 'processPayment',
    )]
    public function sendEmailNotification(AfterMethodInvocation $invocation): void
    {
        $result = $invocation->proceed();
        $amount = $invocation->getArgument('amount');

        $message = sprintf(
            'Payment processed for amount $%.2f',
            $amount,
        );
        if ($result === true) {
            $message .= ' - Payment successful';
        } else {
            $message .= ' - Payment failed';
        }

        $mailQueue = MailQueue::getInstance();
        $mailQueue->addMail($message);
    }
}
