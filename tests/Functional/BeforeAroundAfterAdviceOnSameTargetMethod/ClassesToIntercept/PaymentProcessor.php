<?php
/** @noinspection PhpUnusedParameterInspection */
namespace Okapi\Aop\Tests\Functional\BeforeAroundAfterAdviceOnSameTargetMethod\ClassesToIntercept;

class PaymentProcessor
{
    public function processPayment(float $amount): bool
    {
        // Process payment

        return true;
    }
}
