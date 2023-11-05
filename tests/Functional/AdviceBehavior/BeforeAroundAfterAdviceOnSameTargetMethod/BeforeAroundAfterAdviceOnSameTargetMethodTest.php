<?php
/** @noinspection PhpExpressionResultUnusedInspection */
namespace Okapi\Aop\Tests\Functional\AdviceBehavior\BeforeAroundAfterAdviceOnSameTargetMethod;

use InvalidArgumentException;
use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceBehavior\BeforeAroundAfterAdviceOnSameTargetMethod\Aspect\PaymentProcessorAspect;
use Okapi\Aop\Tests\Functional\AdviceBehavior\BeforeAroundAfterAdviceOnSameTargetMethod\Target\PaymentProcessor;
use Okapi\Aop\Tests\Stubs\Etc\Logger;
use Okapi\Aop\Tests\Stubs\Etc\MailQueue;
use Okapi\Aop\Tests\Util;
use Okapi\Wildcards\Regex;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class BeforeAroundAfterAdviceOnSameTargetMethodTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see PaymentProcessorAspect::checkPaymentAmount()
     * @see PaymentProcessorAspect::logPayment()
     * @see PaymentProcessorAspect::sendEmailNotification()
     */
    public function testBeforeAroundAfterAdviceOnSameTargetMethod(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(PaymentProcessor::class);
        $processor = new PaymentProcessor();

        // Test with an invalid payment amount
        $amount          = -50.00;
        $exceptionThrown = false;
        try {
            $processor->processPayment($amount);
        } catch (InvalidArgumentException $e) {
            $exceptionThrown = true;
            $this->assertSame(
                'Invalid payment amount',
                $e->getMessage(),
            );
        }
        $this->assertTrue($exceptionThrown);

        // Test with a valid payment amount
        $amount  = 420.00;
        $success = $processor->processPayment($amount);
        $this->assertTrue($success);

        // Test that the log message was printed
        $logger = Logger::getInstance();
        $logs   = $logger->getLogs();
        $this->assertCount(1, $logs);
        $logMessage = $logs[0];
        $wildcard   = 'Payment processed for amount $* in * seconds';
        $regex      = Regex::fromWildcard($wildcard);
        $matches    = $regex->matches($logMessage);
        $this->assertTrue($matches);

        // Test that the email notification was sent
        $mailQueue = MailQueue::getInstance();
        $mails     = $mailQueue->getMails();
        $this->assertCount(1, $mails);
        $mail     = $mails[0];
        $wildcard = 'Payment processed for amount $* - Payment successful';
        $regex    = Regex::fromWildcard($wildcard);
        $matches  = $regex->matches($mail);
        $this->assertTrue($matches);
    }
}
