<?php

namespace Okapi\Aop\Attributes\Base;

use Okapi\Wildcards\Regex;

/**
 * # Base advice
 *
 * This class is used as a base for all advice attributes.<br>
 * It should be extended from to categorize the advice types.
 */
abstract class BaseAdvice extends BaseAttribute
{
    /**
     * The order of the advice.
     *
     * @var int
     */
    public int $order = 0;

    public Regex $class;

    /**
     * Base advice constructor.
     *
     * @param string $class Wildcard pattern for the class name.
     */
    public function __construct(
        string $class,
    ) {
        $this->class = Regex::fromWildcard($class);
    }
}
