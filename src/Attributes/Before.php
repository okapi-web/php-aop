<?php

namespace Okapi\Aop\Attributes;

use Attribute;
use Okapi\Aop\Core\Attributes\AdviceType\MethodAdvice;

/**
 * # Before attribute
 *
 * This attribute is used to mark a method as a before advice.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Before extends MethodAdvice
{
}
