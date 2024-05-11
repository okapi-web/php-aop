<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\AdviceBehavior\NewClassCreationWithProxiedClasses\Aspect;

use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Tests\Functional\AdviceBehavior\NewClassCreationWithProxiedClasses\Target\GroupMemberService;
use Okapi\Aop\Tests\Functional\AdviceBehavior\NewClassCreationWithProxiedClasses\Target\GroupPolicy;

#[Aspect]
class ModifyGroupPolicyAspect
{
    #[Around(
        class: GroupMemberService::class,
        method: '*',
    )]
    #[Around(
        class: GroupPolicy::class,
        method: '*',
    )]
    public function doNothing(): void {}
}
