<?php

namespace Okapi\Aop\Core\Attributes\AdviceType;

use Okapi\Aop\Core\Attributes\Base\BaseAdvice;
use Okapi\Wildcards\Regex;

// TODO: docs
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
        int    $order = 0,
    ) {
        parent::__construct($class, $order);
        $this->method = Regex::fromWildcard($method);
    }
}
