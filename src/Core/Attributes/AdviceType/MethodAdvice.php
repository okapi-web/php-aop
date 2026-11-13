<?php

namespace Okapi\Aop\Core\Attributes\AdviceType;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\AfterThrowing;
use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Attributes\Before;
use Okapi\Aop\Core\Attributes\Base\BaseAdvice;
use Okapi\Wildcards\Regex;

/**
 * # Method Advice
 *
 * This class is used as a base for all method advice attributes.<br>
 * It should be extended from to categorize the method advice types.
 *
 * @see Before
 * @see Around
 * @see After
 * @see AfterThrowing
 */
abstract class MethodAdvice extends BaseAdvice
{
    public ?Regex $method;

    /**
     * MethodAdvice constructor.
     *
     * @param string|Regex|null $class           Wildcard string or explicit regular expression for the class name.
     * @param string|Regex|null $method          Wildcard string or explicit regular expression for the method name.
     * @param int         $order                 The order of the advice.
     * @param bool        $interceptTraitMethods If {@see true}, trait methods will be intercepted.
     *                                           [Default: {@see true}]
     * @param bool        $onlyPublicMethods     If {@see true}, only public methods will be intercepted.
     *                                           [Default: {@see false}]
     */
    public function __construct(
        string|Regex|null $class = null,
        string|Regex|null $method = null,
        int $order = 0,
        public bool $interceptTraitMethods = true,
        public bool $onlyPublicMethods = false,
    ) {
        parent::__construct($class, $order);
        $this->method = self::resolvePattern($method, 'method');
    }
}
