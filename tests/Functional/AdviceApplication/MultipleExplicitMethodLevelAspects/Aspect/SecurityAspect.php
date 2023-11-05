<?php

namespace Okapi\Aop\Tests\Functional\AdviceApplication\MultipleExplicitMethodLevelAspects\Aspect;

use Attribute;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Attributes\Before;
use Okapi\Aop\Invocation\BeforeMethodInvocation;

#[Attribute]
#[Aspect]
class SecurityAspect
{
    public const SECRET_HASH = '-secret-hash';

    #[Before]
    public function applySecurityMeasures(BeforeMethodInvocation $invocation): void
    {
        $arguments = $invocation->getArguments();

        $firstArgument = reset($arguments);
        $firstArgumentKey = key($arguments);

        if (gettype($firstArgument) === 'array') {
            $id = &$firstArgument['id'];
            $id .= self::SECRET_HASH;

            $arguments[$firstArgumentKey] = $firstArgument;

            $invocation->setArguments($arguments);
        }

        if (gettype($firstArgument) === 'string') {
            $id = &$firstArgument;
            $id .= self::SECRET_HASH;

            $arguments[$firstArgumentKey] = $id;

            $invocation->setArguments($arguments);
        }
    }
}
