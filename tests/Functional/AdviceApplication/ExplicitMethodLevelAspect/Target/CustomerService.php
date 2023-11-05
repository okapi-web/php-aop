<?php

namespace Okapi\Aop\Tests\Functional\AdviceApplication\ExplicitMethodLevelAspect\Target;

use Okapi\Aop\Tests\Functional\AdviceApplication\ExplicitMethodLevelAspect\Aspect\PerformanceAspect;

class CustomerService
{
    #[PerformanceAspect]
    public function createCustomer(): void
    {
        // Logic to create a customer
    }
}
