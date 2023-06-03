<?php

namespace Okapi\Aop\Core\Factory;

use Okapi\Aop\Attributes\{After, Around, Before};
use Okapi\Aop\Core\Container\AdviceType\MethodAdviceContainer;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Invocation\AroundMethodInvocation;
use Okapi\Aop\Invocation\BeforeMethodInvocation;
use Okapi\Aop\Invocation\MethodInvocation;
use Okapi\CodeTransformer\Core\DI;

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
     *
     * @return MethodInvocation
     *
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    public function getInvocation(
        MethodAdviceContainer $adviceContainer,
        ?object               $subject,
        string                $className,
        string                $methodName,
        mixed                 $result,
        array                 &$arguments,
    ): MethodInvocation {
        switch (true) {
            // Before
            case $adviceContainer->adviceAttributeInstance instanceof Before:
                return DI::make(BeforeMethodInvocation::class, [
                    'subject'    => $subject,
                    'className'  => $className,
                    'methodName' => $methodName,
                    'result'     => $result,
                    'arguments'  => &$arguments,
                ]);

            // Around
            case $adviceContainer->adviceAttributeInstance instanceof Around:
                return DI::make(AroundMethodInvocation::class, [
                    'subject'    => $subject,
                    'className'  => $className,
                    'methodName' => $methodName,
                    'result'     => $result,
                    'arguments'  => &$arguments,
                ]);

            // After
            case $adviceContainer->adviceAttributeInstance instanceof After:
                return DI::make(AfterMethodInvocation::class, [
                    'subject'    => $subject,
                    'className'  => $className,
                    'methodName' => $methodName,
                    'result'     => $result,
                    'arguments'  => &$arguments,
                ]);
        }
    }
}
