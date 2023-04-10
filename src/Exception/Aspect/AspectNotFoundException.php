<?php

namespace Okapi\Aop\Exception\Aspect;

use Okapi\Aop\Exception\AspectException;

/**
 * # Aspect Not Found Exception
 *
 * This exception is thrown when an aspect is not found.
 */
class AspectNotFoundException extends AspectException
{
    /**
     * AspectNotFoundException constructor.
     *
     * @param string $aspectName
     */
    public function __construct(string $aspectName)
    {
        parent::__construct(
            "Aspect \"$aspectName\" not found.",
        );
    }
}
