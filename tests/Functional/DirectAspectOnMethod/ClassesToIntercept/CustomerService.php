<?php

namespace Okapi\Aop\Tests\Functional\DirectAspectOnMethod\ClassesToIntercept;

use Okapi\Aop\Tests\Functional\DirectAspectOnMethod\Aspect\PerformanceAspect;

class CustomerService
{
    #[PerformanceAspect]
    public function createCustomer(): void
    {
        // Logic to create a customer
    }
}
