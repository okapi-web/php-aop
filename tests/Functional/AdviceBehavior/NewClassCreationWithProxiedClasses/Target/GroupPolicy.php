<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\NewClassCreationWithProxiedClasses\Target;

class GroupPolicy
{
    public function getPolicyDetails(): string
    {
        return 'Original Policy Details';
    }
}
