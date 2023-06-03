<?php
/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Core\Invocation;

use DI\Attribute\Inject;
use Okapi\Aop\Attributes\After;
use Okapi\Aop\Core\Container\AdviceType\MethodAdviceContainer;
use Okapi\Aop\Core\Factory\InvocationFactory;
use Okapi\Aop\Invocation\MethodInvocation;

/**
 * # Advice Chain
 *
 * This class is responsible for executing the advice chain.
 */
class AdviceChain
{
    // region DI

    #[Inject]
    private InvocationFactory $invocationFactory;

    // endregion

    private int $currentInterceptorIndex = 0;

    /**
     * Stores the result of the target method.
     *
     * @var mixed|null
     */
    private mixed $result = null;

    /**
     * Whether the result has been manually set.
     *
     * @var bool
     */
    private bool $resultHasBeenSet = false;

    /**
     * AroundAdviceChain constructor.
     *
     * @param MethodAdviceContainer[] $interceptors
     * @param object|null             $subject
     * @param string                  $className
     * @param string                  $methodName
     * @param array                   $arguments
     * @param callable|null           $originalMethod
     * @param mixed                   $resultFromOriginalMethod
     *
     * @noinspection PhpMissingParamTypeInspection
     */
    public function __construct(
        private readonly array   $interceptors,
        private readonly ?object $subject,
        private readonly string  $className,
        private readonly string  $methodName,
        private array            &$arguments,
        private                  $originalMethod = null,
        private readonly mixed   $resultFromOriginalMethod = null,
    ) {
        if ($this->interceptors[0]->adviceAttributeInstance instanceof After) {
            $this->setResult($this->resultFromOriginalMethod);
        }
    }

    /**
     * Proceed to next interceptor or target method.
     *
     * @param bool $allowRepeatedCalls
     *
     * @return mixed
     *
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function proceed(bool $allowRepeatedCalls = false): mixed
    {
        if ($this->currentInterceptorIndex < count($this->interceptors)) {
            $interceptor = $this->interceptors[$this->currentInterceptorIndex];
            $this->currentInterceptorIndex++;

            $aspectInstance  = $interceptor->aspectInstance;
            $adviceRefMethod = $interceptor->adviceRefMethod;

            // Get invocation
            /** @var MethodInvocation&AdviceChainAwareTrait $invocation */
            $invocation = $this->invocationFactory->getInvocation(
                adviceContainer: $interceptor,
                subject: $this->subject,
                className: $this->className,
                methodName: $this->methodName,
                result: null,
                arguments: $this->arguments,
            );

            // Set advice chain to invocation
            $invocation->setAdviceChain($this);

            // Call the advice method
            $result = $adviceRefMethod->invoke($aspectInstance, $invocation);

            // Check if the advice method will return a value
            $hasReturnValue = $adviceRefMethod->getReturnType()?->getName() !== 'void';

            // 1. Accept return value of advice method OR
            // 2. Check if the advice method used "setResult()" OR
            // 3. Proceed with the next advice in the chain
            if ($hasReturnValue && $result !== null) {
                // If the advice returns null instead of using "setResult()"
                // and does not have a return type we ignore the return value,
                // because it's not possible to distinguish between a null
                // return value and a void return value.

                $this->setResult($result);
            }

            return $this->proceed($allowRepeatedCalls);
        }

        // 1. Check if the result has been set AND if repeated calls are allowed OR
        // 2. Check if the original method is available OR
        // 3. Return the result from the original method
        if ($this->resultHasBeenSet && !$allowRepeatedCalls) {
            return $this->result;
        } elseif ($this->originalMethod) {
            $result = ($this->originalMethod)(...array_values($this->arguments));
            $this->setResult($result);
            return $result;
        } else {
            return $this->resultFromOriginalMethod;
        }
    }

    /**
     * Set result.
     *
     * @param mixed $result
     *
     * @return void
     */
    public function setResult(mixed $result): void
    {
        $this->result = $result;

        $this->resultHasBeenSet = true;
    }
}
