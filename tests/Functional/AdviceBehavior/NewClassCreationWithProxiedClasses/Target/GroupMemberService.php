<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\NewClassCreationWithProxiedClasses\Target;

class GroupMemberService
{
    private GroupPolicy $groupPolicy;

    public function __construct(GroupPolicy $groupPolicy)
    {
        $this->groupPolicy = $groupPolicy;
    }

    public function getPolicyDetails(): string
    {
        return $this->groupPolicy->getPolicyDetails();
    }
}
