<?php

namespace Okapi\Aop\Exception\Aspect;

use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Exception\AspectException;

/**
 * # Missing Aspect Attribute Exception
 *
 * This exception is thrown when an aspect is missing the #[{@link Aspect}]
 * attribute.
 */
class MissingAspectAttributeException extends AspectException
{
    /**
     * MissingAspectAttributeException constructor.
     *
     * @param string $aspectName
     */
    public function __construct(string $aspectName)
    {
        parent::__construct(
            "Aspect '$aspectName' is missing the #[Aspect] attribute."
        );
    }
}
