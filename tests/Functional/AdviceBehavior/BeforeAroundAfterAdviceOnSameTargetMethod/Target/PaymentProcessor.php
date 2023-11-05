<?php
/** @noinspection PhpUnusedParameterInspection */
namespace Okapi\Aop\Tests\Functional\AdviceBehavior\BeforeAroundAfterAdviceOnSameTargetMethod\Target;

class PaymentProcessor
{
    public function processPayment(float $amount): bool
    {
        // Process payment

        return true;
    }
}
