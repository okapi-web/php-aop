<?php

namespace Okapi\Aop\Core\Container;

use Okapi\Aop\Core\Container\JoinPoint\MethodJoinPointContainer;
use Okapi\Aop\Core\JoinPoint\JoinPoint;
use Okapi\CodeTransformer\Core\DI;

/**
 * # Join Point Container
 *
 * This class is used to read the join points from the woven class and convert
 * them to interceptors.
 */
class JoinPointContainer
{
    /**
     * @var MethodJoinPointContainer[]
     */
    private array $methodJoinPointContainers = [];

    /**
     * JoinPointContainer constructor.
     *
     * @param class-string                             $className
     * @param array<'method', array<string, string[]>> $joinPointPropertyValue
     */
    public function __construct(
        string $className,
        array  $joinPointPropertyValue,
    ) {
        foreach ($joinPointPropertyValue as $joinPointType => $joinPointValue) {
            if ($joinPointType === JoinPoint::TYPE_METHOD) {
                foreach ($joinPointValue as $methodName => $joinPoints) {
                    $this->methodJoinPointContainers[] = DI::make(
                        MethodJoinPointContainer::class,
                        [
                            'className'  => $className,
                            'methodName' => $methodName,
                            'joinPoints' => $joinPoints,
                        ],
                    );
                }
            }
        }
    }

    /**
     * Get the value of the join point container.
     *
     * @return array<'method', array<string, string[]>>
     */
    public function getValue(): array
    {
        $value = [];

        foreach ($this->methodJoinPointContainers as $methodJoinPointContainer) {
            $methodName     = $methodJoinPointContainer->methodName;
            $joinPointValue = $methodJoinPointContainer->getValue();

            $value[JoinPoint::TYPE_METHOD][$methodName] = $joinPointValue;
        }

        return $value;
    }
}
