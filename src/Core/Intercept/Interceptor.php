<?php
/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Core\Intercept;

use DI\Attribute\Inject;
use Okapi\Aop\Core\Container\AdviceType\MethodAdviceContainer;
use Okapi\Aop\Core\Factory\InvocationFactory;
use Okapi\Aop\Core\Invocation\AdviceChain;
use Okapi\Aop\Core\JoinPoint\JoinPointHandler;
use Okapi\CodeTransformer\Core\DI;
use ReflectionClass as BaseReflectionClass;
use ReflectionMethod;

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
        array                   $joinPoints,
    ) {
        $joinPointHandler = DI::make(JoinPointHandler::class, [
            'className'  => $className,
            'joinPoints' => $joinPoints,
        ]);

        $joinPointHandler->handle(
            $this->beforeInterceptors,
            $this->aroundInterceptors,
            $this->afterInterceptors,
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
            $aspectInstance  = $beforeInterceptor->aspectInstance;
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

        if ($this->aroundInterceptors) {
            $aroundAdviceChain = DI::make(AdviceChain::class, [
                'interceptors'   => $this->aroundInterceptors,
                'subject'        => $subject,
                'className'      => $this->className,
                'methodName'     => $this->methodName,
                'arguments'      => &$arguments,
                'originalMethod' => function () use ($subject, &$arguments) {
                    return $this->callParentMethod($subject, $arguments);
                },
            ]);

            $result = $aroundAdviceChain->proceed();
        } else {
            $result = $this->callParentMethod($subject, $arguments);
        }

        if ($this->afterInterceptors) {
            $afterAdviceChain = DI::make(AdviceChain::class, [
                'interceptors'             => $this->afterInterceptors,
                'subject'                  => $subject,
                'className'                => $this->className,
                'methodName'               => $this->methodName,
                'arguments'                => &$arguments,
                'originalMethod'           => function () use ($subject, &$arguments) {
                    return $this->callParentMethod($subject, $arguments);
                },
                'resultFromOriginalMethod' => $result,
            ]);

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

        $parentMethod = $parentClass->getMethod($this->methodName);

        $this->unwrapVariadicParameters($parentMethod, $args);

        return $parentMethod->invoke($subject, ...array_values($args));
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

            $variadicParameterValues = array_values($args[$lastParameterName]);

            unset($args[$lastParameterName]);

            $args = array_merge($args, $variadicParameterValues);
        }
    }
}
