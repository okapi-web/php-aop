<?php

namespace Okapi\Aop\Invocation;

use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Core\Invocation\AdviceChainAwareTrait;

/**
 * # Around method invocation
 *
 * This class is used to pass information to {@see Around} advices in form of a
 * parameter.
 */
class AroundMethodInvocation extends MethodInvocation
{
    use AdviceChainAwareTrait;
}
