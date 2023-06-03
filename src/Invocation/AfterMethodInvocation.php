<?php

namespace Okapi\Aop\Invocation;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Core\Invocation\AdviceChainAwareTrait;

/**
 * # After method invocation
 *
 * This class is used to pass information to {@see After} advices in form of a
 * parameter.
 */
class AfterMethodInvocation extends MethodInvocation
{
    use AdviceChainAwareTrait;
}
