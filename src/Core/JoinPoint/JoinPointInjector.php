<?php

namespace Okapi\Aop\Core\JoinPoint;

use Okapi\Aop\Core\Container\JoinPointContainer;
use Okapi\CodeTransformer\Core\DI;
use ReflectionClass as BaseReflectionClass;

/**
 * # Join Point Injector
 *
 * This class is used to inject the join points into the woven class.
 */
class JoinPointInjector
{
    /**
     * Inject the join points into the given class.
     *
     * @param class-string $className
     *
     * @return void
     *
     * @noinspection PhpDocMissingThrowsInspection
     * @noinspection PhpUnused
     */
    public function injectJoinPoints(string $className): void
    {
        // Create reflection from the woven class
        /** @noinspection PhpUnhandledExceptionInspection */
        $refClass = new BaseReflectionClass($className);

        // Read the join points
        $staticPropertyValue = $refClass->getStaticPropertyValue(
            JoinPoint::JOIN_POINTS_PARAMETER_NAME,
        );

        // Convert to JoinPointContainer
        $joinPointContainer = DI::make(JoinPointContainer::class, [
            'className'              => $className,
            'joinPointPropertyValue' => $staticPropertyValue,
        ]);

        // Set the join points
        $refClass->setStaticPropertyValue(
            JoinPoint::JOIN_POINTS_PARAMETER_NAME,
            $joinPointContainer->getValue(),
        );
    }
}
