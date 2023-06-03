<?php

namespace Okapi\Aop\Attributes;

use Attribute;
use Okapi\Aop\Core\Attributes\AdviceType\MethodAdvice;

/**
 * # Around attribute
 *
 * This attribute is used to mark a method as an around advice.
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Around extends MethodAdvice
{
}
