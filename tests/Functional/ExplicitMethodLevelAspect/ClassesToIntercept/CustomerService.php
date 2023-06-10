<?php

namespace Okapi\Aop\Tests\Functional\ExplicitMethodLevelAspect\ClassesToIntercept;

use Okapi\Aop\Tests\Functional\ExplicitMethodLevelAspect\Aspect\PerformanceAspect;

class CustomerService
{
    #[PerformanceAspect]
    public function createCustomer(): void
    {
        // Logic to create a customer
    }
}
