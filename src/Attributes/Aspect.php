<?php

namespace Okapi\Aop\Attributes;

use Attribute;
use Okapi\Aop\Attributes\Base\BaseAdvice;

/**
 * # Aspect attribute
 *
 * This attribute is used to mark a class as an aspect.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Aspect extends BaseAdvice
{
}
