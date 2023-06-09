<?php

namespace Okapi\Aop\Core\Exception\Advice;

use Okapi\Aop\Core\Exception\AdviceException;

/**
 * # Missing Class Name Exception
 *
 * This exception is thrown when an advice is missing the class name.
 */
class MissingClassNameException extends AdviceException
{
    /**
     * MissingClassNameException constructor.
     */
    public function __construct(string $adviceName)
    {
        parent::__construct(
            "Advice \"$adviceName\" is being used explicitly and is " .
            "missing the class name.",
        );
    }
}
