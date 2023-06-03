<?php

namespace Okapi\Aop\Core\Container\JoinPoint;

use Okapi\Aop\Core\Intercept\Interceptor;
use Okapi\CodeTransformer\Core\DI;

// TODO: docs
class MethodJoinPointContainer
{
    private Interceptor $interceptor;

    /**
     * MethodJoinPointContainer constructor.
     *
     * @param class-string $className
     * @param string       $methodName
     * @param string[]     $joinPoints
     */
    public function __construct(
        string                 $className,
        public readonly string $methodName,
        array                  $joinPoints,
    ) {
        $this->interceptor = DI::make(Interceptor::class, [
            'className'  => $className,
            'methodName' => $methodName,
            'joinPoints' => $joinPoints,
        ]);
    }

    // TODO: docs
    public function getValue(): array
    {
        return [
            $this->interceptor,
            Interceptor::METHOD_NAME,
        ];
    }
}
