<?php
/** @noinspection PhpUnusedParameterInspection */
namespace Okapi\Aop\Tests\Stubs\ClassesToIntercept\BeforeAroundAfterAdviceOnSameTargetMethod;

class PaymentProcessor
{
    public function processPayment(float $amount): bool
    {
        // Process payment

        return true;
    }
}
