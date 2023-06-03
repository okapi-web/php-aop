<?php

namespace Okapi\Aop\Core\Exception\Aspect;

use Okapi\Aop\Core\Exception\AspectException;

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
            'Aspect class name in Kernel must be a string.',
        );
    }
}
