<?php

namespace Okapi\Aop\Core\Exception\Advice;

use Okapi\Aop\Core\Exception\AdviceException;

/**
 * # Missing Method Name Exception
 *
 * This exception is thrown when an advice is missing the method name.
 */
class MissingMethodNameException extends AdviceException
{
    /**
     * MissingMethodNameException constructor.
     */
    public function __construct(string $adviceName)
    {
        parent::__construct(
            "Advice \"$adviceName\" is being used explicitly and is " .
            "missing the method name.",
        );
    }
}
