<?php

namespace Okapi\Aop\Exception\Aspect;

use Okapi\Aop\Exception\AspectException;

/**
 * # Invalid Aspect Class Name Exception
 *
 * This exception is thrown when an aspect is invalid.
 */
class InvalidAspectClassNameException extends AspectException
{
    /**
     * InvalidAspectClassNameException constructor.
     */
    public function __construct()
    {
        parent::__construct(
            "Aspect class name in Kernel must be a string.",
        );
    }
}
