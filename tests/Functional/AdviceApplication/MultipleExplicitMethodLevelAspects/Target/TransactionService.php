<?php

namespace Okapi\Aop\Tests\Functional\AdviceApplication\MultipleExplicitMethodLevelAspects\Target;

use Exception;
use Okapi\Aop\Tests\Functional\AdviceApplication\MultipleExplicitMethodLevelAspects\Aspect\SecurityAspect;

class TransactionService
{
    private array $transactions = [];

    #[SecurityAspect]
    public function createTransaction(array $transactionData): void
    {
        $this->transactions[] = $transactionData['id'];
    }

    #[SecurityAspect]
    public function rollbackTransaction(string $transactionId): void
    {
        $transactionIndex = array_search($transactionId, $this->transactions);

        if ($transactionIndex === false) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new Exception("Transaction with id $transactionId not found.");
        }

        unset($this->transactions[$transactionIndex]);
    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }
}
