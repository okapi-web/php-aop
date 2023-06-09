<?php

namespace Okapi\Aop\Core\Container;

use Attribute;
use Okapi\Aop\Core\Attributes\AdviceType\MethodAdvice;
use Okapi\Aop\Core\Container\AdviceType\MethodAdviceContainer;
use Okapi\Aop\Core\Exception\Advice\MissingClassNameException;
use Okapi\Aop\Core\Exception\Advice\MissingMethodNameException;
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
     *
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    public function createAdviceContainer(
        string                  $aspectClassName,
        object                  $aspectInstance,
        BaseReflectionClass     $aspectRefClass,
        BaseReflectionAttribute $adviceAttribute,
        BaseReflectionMethod    $adviceRefMethod,
    ): AdviceContainer {
        // Instantiate the advice attribute
        $adviceAttributeInstance = $adviceAttribute->newInstance();

        // Check if the aspect are implicit or class/method-level explicit
        $isImplicit = (bool)$aspectRefClass->getAttributes(Attribute::class);

        if ($adviceAttributeInstance instanceof MethodAdvice) {
            $methodAdviceContainer = DI::make(MethodAdviceContainer::class, [
                'aspectClassName'         => $aspectClassName,
                'aspectInstance'          => $aspectInstance,
                'aspectRefClass'          => $aspectRefClass,
                'adviceAttribute'         => $adviceAttribute,
                'adviceAttributeInstance' => $adviceAttributeInstance,
                'adviceRefMethod'         => $adviceRefMethod,
                'isImplicit'              => $isImplicit,
            ]);

            // If the aspect is explicit,
            // check if the class and method names are set
            if (!$isImplicit) {
                if (!$adviceAttributeInstance->class) {
                    throw new MissingClassNameException(
                        $methodAdviceContainer->getName(),
                    );
                }

                if (!$adviceAttributeInstance->method) {
                    throw new MissingMethodNameException(
                        $methodAdviceContainer->getName(),
                    );
                }
            }

            return $methodAdviceContainer;
        }
    }
}
