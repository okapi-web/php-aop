<?php

namespace Okapi\Aop\Core\Factory;

use LogicException;
use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\AfterThrowing;
use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Attributes\Before;
use Okapi\Aop\Core\Container\AdviceType\MethodAdviceContainer;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Invocation\AfterThrowingMethodInvocation;
use Okapi\Aop\Invocation\AroundMethodInvocation;
use Okapi\Aop\Invocation\BeforeMethodInvocation;
use Okapi\Aop\Invocation\MethodInvocation;
use Okapi\CodeTransformer\Core\DI;
use Throwable;

/**
 * # Invocation Factory
 *
 * This class is responsible for creating the correct invocation object.
 */
class InvocationFactory
{
    /**
     * InvocationFactory constructor.
     *
     * @param MethodAdviceContainer $adviceContainer
     * @param object|null           $subject
     * @param string                $className
     * @param string                $methodName
     * @param mixed                 $result
     * @param array                 $arguments
     * @param Throwable|null        $throwable The target failure for AfterThrowing advice.
     *
     * @return MethodInvocation
     */
    public function getInvocation(
        MethodAdviceContainer $adviceContainer,
        ?object $subject,
        string $className,
        string $methodName,
        mixed $result,
        array &$arguments,
        ?Throwable $throwable = null,
    ): MethodInvocation {
        switch (true) {
            case $adviceContainer->adviceAttributeInstance instanceof AfterThrowing:
                if ($throwable === null) {
                    throw new LogicException('AfterThrowing advice requires a target throwable.');
                }
                return DI::make(AfterThrowingMethodInvocation::class, [
                    'subject' => $subject,
                    'className' => $className,
                    'methodName' => $methodName,
                    'arguments' => &$arguments,
                    'throwable' => $throwable,
                ]);

            // Before
            case $adviceContainer->adviceAttributeInstance instanceof Before:
                return DI::make(BeforeMethodInvocation::class, [
                    'subject' => $subject,
                    'className' => $className,
                    'methodName' => $methodName,
                    'result' => $result,
                    'arguments' => &$arguments,
                ]);

            // Around
            case $adviceContainer->adviceAttributeInstance instanceof Around:
                return DI::make(AroundMethodInvocation::class, [
                    'subject' => $subject,
                    'className' => $className,
                    'methodName' => $methodName,
                    'result' => $result,
                    'arguments' => &$arguments,
                ]);

            // After
            case $adviceContainer->adviceAttributeInstance instanceof After:
                return DI::make(AfterMethodInvocation::class, [
                    'subject' => $subject,
                    'className' => $className,
                    'methodName' => $methodName,
                    'result' => $result,
                    'arguments' => &$arguments,
                ]);
        }
        throw new LogicException('Unsupported advice type.');
    }
}
