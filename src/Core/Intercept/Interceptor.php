<?php

/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Core\Intercept;

use DI\Attribute\Inject;
use LogicException;
use Okapi\Aop\Attributes\AfterThrowing;
use Okapi\Aop\Core\Container\AdviceType\MethodAdviceContainer;
use Okapi\Aop\Core\Factory\InvocationFactory;
use Okapi\Aop\Core\Invocation\AdviceChain;
use Okapi\Aop\Core\JoinPoint\JoinPointHandler;
use Okapi\CodeTransformer\Core\DI;
use ReflectionClass as BaseReflectionClass;
use ReflectionMethod;
use Throwable;

/**
 * # Interceptor
 *
 * This class is used to intercept the target method.
 */
class Interceptor
{
    // region DI

    #[Inject]
    private InvocationFactory $invocationFactory;

    // endregion

    public const METHOD_NAME = 'intercept';

    /** @var MethodAdviceContainer[] */
    private array $beforeInterceptors = [];

    /** @var MethodAdviceContainer[] */
    private array $aroundInterceptors = [];

    /** @var MethodAdviceContainer[] */
    private array $afterInterceptors = [];

    /**
     * @var MethodAdviceContainer[]
     */
    private array $afterThrowingInterceptors = [];

    /**
     * The target class reflection.
     *
     * @var BaseReflectionClass
     */
    private BaseReflectionClass $targetRefClass;

    /**
     * Interceptor constructor.
     *
     * @param class-string $className
     * @param string       $methodName
     * @param string[]     $joinPoints
     *
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function __construct(
        private readonly string $className,
        private readonly string $methodName,
        array $joinPoints,
    ) {
        $joinPointHandler = DI::make(JoinPointHandler::class, [
            'className' => $className,
            'joinPoints' => $joinPoints,
        ]);

        $joinPointHandler->handle(
            $this->beforeInterceptors,
            $this->aroundInterceptors,
            $this->afterInterceptors,
            $this->afterThrowingInterceptors,
        );

        $this->targetRefClass = new BaseReflectionClass($className);
    }

    /**
     * Intercept the target method.
     *
     * @param object|null              $subject
     * @param array<string|int, mixed> $arguments
     *
     * @return mixed
     *
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection PhpDocMissingThrowsInspection
     * @noinspection PhpUnused
     */
    public function intercept(?object $subject, array $arguments = []): mixed
    {
        foreach ($this->beforeInterceptors as $beforeInterceptor) {
            $aspectInstance = $beforeInterceptor->aspectInstance;
            $adviceRefMethod = $beforeInterceptor->adviceRefMethod;

            $invocation = $this->invocationFactory->getInvocation(
                adviceContainer: $beforeInterceptor,
                subject: $subject,
                className: $this->className,
                methodName: $this->methodName,
                result: null,
                arguments: $arguments,
            );

            $adviceRefMethod->invoke($aspectInstance, $invocation);

            $arguments = $invocation->getArguments();
        }

        $aroundAdviceChain = null;
        if ($this->aroundInterceptors) {
            $aroundAdviceChain = DI::make(AdviceChain::class, [
                'interceptors' => $this->aroundInterceptors,
                'subject' => $subject,
                'className' => $this->className,
                'methodName' => $this->methodName,
                'arguments' => &$arguments,
                'originalMethod' => function () use ($subject, &$arguments) {
                    /** @var array<array-key, mixed> $arguments */
                    return $this->callParentMethod($subject, $arguments);
                },
            ]);
        }
        /** @var array<array-key, mixed> $arguments */
        /** @var mixed $result */
        $result = $aroundAdviceChain !== null
            ? $aroundAdviceChain->proceed()
            : $this->callParentMethod($subject, $arguments);

        if ($this->afterInterceptors) {
            $afterAdviceChain = DI::make(AdviceChain::class, [
                'interceptors' => $this->afterInterceptors,
                'subject' => $subject,
                'className' => $this->className,
                'methodName' => $this->methodName,
                'arguments' => &$arguments,
                'originalMethod' => function () use ($subject, &$arguments) {
                    /** @var array<array-key, mixed> $arguments */
                    return $this->callParentMethod($subject, $arguments);
                },
                'resultFromOriginalMethod' => $result,
            ]);

            /** @var mixed $result */
            $result = $afterAdviceChain->proceed();
        }

        return $result;
    }

    /**
     * Call parent method.
     *
     * @param object|null $subject
     * @param array       $args
     *
     * @return mixed
     *
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection PhpDocMissingThrowsInspection
     */
    private function callParentMethod(?object $subject, array $args): mixed
    {
        $parentClass = $this->targetRefClass->getParentClass();

        if ($parentClass === false) {
            throw new LogicException('An intercepted class must have a woven parent.');
        }
        $parentMethod = $parentClass->getMethod($this->methodName);

        $invocationArguments = $args;
        $this->unwrapVariadicParameters($parentMethod, $args);

        try {
            return $parentMethod->invokeArgs($subject, array_values($args));
        } catch (Throwable $throwable) {
            foreach ($this->afterThrowingInterceptors as $interceptor) {
                $advice = $interceptor->adviceAttributeInstance;
                if (!$advice instanceof AfterThrowing || !is_a($throwable::class, $advice->exception, true)) {
                    continue;
                }

                $invocation = $this->invocationFactory->getInvocation(
                    adviceContainer: $interceptor,
                    subject: $subject,
                    className: $this->className,
                    methodName: $this->methodName,
                    result: null,
                    arguments: $invocationArguments,
                    throwable: $throwable,
                );

                $interceptor->adviceRefMethod->invoke($interceptor->aspectInstance, $invocation);
            }
            throw $throwable;
        }
    }

    /**
     * Unwrap variadic parameters.
     *
     * @param ReflectionMethod $method
     * @param array             $args
     *
     * @return void
     */
    private function unwrapVariadicParameters(ReflectionMethod $method, array &$args): void
    {
        $parameters = $method->getParameters();

        $lastParameter = end($parameters);

        if ($lastParameter && $lastParameter->isVariadic()) {
            $lastParameterName = $lastParameter->getName();

            /** @var mixed $variadicArguments */
            $variadicArguments = $args[$lastParameterName];
            if (!is_array($variadicArguments)) {
                throw new LogicException('Variadic arguments must be an array.');
            }

            $variadicParameterValues = array_values($variadicArguments);

            unset($args[$lastParameterName]);

            $args = array_merge($args, $variadicParameterValues);
        }
    }
}
