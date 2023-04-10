<?php

namespace Okapi\Aop\Attributes\Base;

use Okapi\Wildcards\Regex;

abstract class MethodAdvice extends BaseAdvice
{
    public Regex $method;

    /**
     * MethodAdvice constructor.
     *
     * @param string $class  Wildcard pattern for the class name.
     * @param string $method Wildcard pattern for the method name.
     */
    public function __construct(
        string $class,
        string $method,
    ) {
        parent::__construct($class);
        $this->method  = Regex::fromWildcard($method);
    }
}
