<?php

namespace Okapi\Aop\Attributes;

use Attribute;
use Okapi\Aop\Core\Attributes\Base\BaseAttribute;

/**
 * # Aspect attribute
 *
 * This attribute is used to mark a class as an aspect.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Aspect extends BaseAttribute
{
}
