<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ProtectedAndPrivateMethods\Target;

class BankingSystem
{
    public const DEPOSIT_FEE_PERCENTAGE = 2;
    public const WITHDRAW_FEE_PERCENTAGE = 5;

    public float $balance = 0.0;

    public function deposit(float $amount): void
    {
        $this->balance += $this->removeFeeFromDeposit($amount);
    }

    protected function removeFeeFromDeposit(float $amount): float
    {
        return $amount - ($amount * self::DEPOSIT_FEE_PERCENTAGE / 100);
    }

    public function withdraw(float $amount): void
    {
        $this->balance -= $this->addFeeToWithdraw($amount);
    }

    private function addFeeToWithdraw(float $amount): float
    {
        return $amount + ($amount * self::WITHDRAW_FEE_PERCENTAGE / 100);
    }

    public function getBalance(): float
    {
        return $this->balance;
    }
}
