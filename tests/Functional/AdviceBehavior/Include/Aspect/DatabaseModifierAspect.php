<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\Include\Aspect;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Tests\Functional\AdviceBehavior\Include\Target\SecureDatabaseService;

#[Aspect]
class DatabaseModifierAspect
{
    #[After(
        class: SecureDatabaseService::class,
        method: 'load',
    )]
    public function modifyData(AfterMethodInvocation $invocation): void
    {
        /** @var SecureDatabaseService $subject */
        $subject = $invocation->getSubject();

        $subject->data = [
            'd' => 4,
            'e' => 5,
            'f' => 6,
        ];
    }
}
