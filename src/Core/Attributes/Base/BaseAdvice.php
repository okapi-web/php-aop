<?php

namespace Okapi\Aop\Core\Attributes\Base;

use Okapi\Aop\Core\Attributes\AdviceType\MethodAdvice;
use Okapi\Aop\Tests\Functional\MethodAdvice\Aspect\PerformanceAspect;
use Okapi\Wildcards\Regex;

/**
 * # Base advice
 *
 * This class is used as a base for all advice attributes.<br>
 * It should be extended from to categorize the advice types.
 *
 * @see MethodAdvice
 */
abstract class BaseAdvice extends BaseAttribute
{
    public ?Regex $class;
    /**
     * Do not process methods defined in parent class.
     */
    public bool $bypassParent ;
    /**
     * Do not process methods defined in trait.
     */
    public bool $bypassTraits ;

    /**
     * Base advice constructor.
     *
     * @param string|null $class Wildcard pattern for the class name.
     * @param int         $order The order of the advice.
     */
    public function __construct(
        ?string    $class = null,
        public int $order = 0,
        bool $bypassParent = false,
        bool $bypassTraits = false,
    ) {
        $this->class = $class ? Regex::fromWildcard($class) : null;
        $this->bypassParent = $bypassParent ;
        $this->bypassTraits = $bypassTraits ;
    }
}
