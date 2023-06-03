<?php

namespace Okapi\Aop\Core\Attributes\Base;

use Okapi\Wildcards\Regex;

/**
 * # Base advice
 *
 * This class is used as a base for all advice attributes.<br>
 * It should be extended from to categorize the advice types.
 */
abstract class BaseAdvice extends BaseAttribute
{
    public Regex $class;

    /**
     * Base advice constructor.
     *
     * @param string $class Wildcard pattern for the class name.
     * @param int    $order The order of the advice.
     */
    public function __construct(
        string     $class,
        public int $order = 0,
    ) {
        $this->class = Regex::fromWildcard($class);
    }
}
