<?php

namespace Okapi\Aop\Core\Container;

use Okapi\Aop\Core\Attributes\AdviceType\MethodAdvice;
use Okapi\Aop\Core\Container\AdviceType\MethodAdviceContainer;
use Okapi\CodeTransformer\Core\DI;
use ReflectionAttribute as BaseReflectionAttribute;
use ReflectionClass as BaseReflectionClass;
use ReflectionMethod as BaseReflectionMethod;

/**
 * # Advice Container Factory
 *
 * This class is responsible for creating advice containers.
 */
class AdviceContainerFactory
{
    /**
     * Create advice container.
     *
     * @param class-string            $aspectClassName
     * @param object                  $aspectInstance
     * @param BaseReflectionClass     $aspectRefClass
     * @param BaseReflectionAttribute $adviceAttribute
     * @param BaseReflectionMethod    $adviceRefMethod
     *
     * @return AdviceContainer
     */
    public function createAdviceContainer(
        string                  $aspectClassName,
        object                  $aspectInstance,
        BaseReflectionClass     $aspectRefClass,
        BaseReflectionAttribute $adviceAttribute,
        BaseReflectionMethod    $adviceRefMethod,
    ): AdviceContainer {
        $adviceAttributeInstance = $adviceAttribute->newInstance();

        /** @noinspection PhpSwitchStatementWitSingleBranchInspection */
        switch (true) {
            case $adviceAttributeInstance instanceof MethodAdvice:
                return DI::make(MethodAdviceContainer::class, [
                    'aspectClassName'         => $aspectClassName,
                    'aspectInstance'          => $aspectInstance,
                    'aspectRefClass'          => $aspectRefClass,
                    'adviceAttribute'         => $adviceAttribute,
                    'adviceAttributeInstance' => $adviceAttributeInstance,
                    'adviceRefMethod'         => $adviceRefMethod,
                ]);
        }
    }
}
