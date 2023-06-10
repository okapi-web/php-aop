<?php

namespace Okapi\Aop\Invocation;

use Okapi\Aop\Advice\AdviceType;

/**
 * # Method invocation
 *
 * Base class for all method invocations.
 */
abstract class MethodInvocation
{
    /**
     * MethodInvocation constructor.
     *
     * @param object|null $subject
     * @param string      $className
     * @param string      $methodName
     * @param mixed       $result
     * @param array       $arguments
     */
    public function __construct(
        private readonly ?object $subject,
        private readonly string  $className,
        private readonly string  $methodName,
        protected mixed          $result,
        private array            &$arguments,
    ) {}

    /**
     * Get an argument by name or index.
     *
     * @param int|string $nameOrIndex
     *
     * @return mixed
     */
    public function getArgument(int|string $nameOrIndex): mixed
    {
        if (is_int($nameOrIndex)) {
            return array_values($this->arguments)[$nameOrIndex] ?? null;
        } else {
            return $this->arguments[$nameOrIndex] ?? null;
        }
    }

    /**
     * Set an argument by name or index.
     *
     * @param int|string $nameOrIndex
     * @param mixed      $value
     *
     * @return void
     */
    public function setArgument(int|string $nameOrIndex, mixed $value): void
    {
        if (is_int($nameOrIndex)) {
            $this->arguments[array_keys($this->arguments)[$nameOrIndex]] = $value;
        } else {
            $this->arguments[$nameOrIndex] = $value;
        }
    }

    /**
     * Get all arguments.
     *
     * @return array<string, mixed>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Set new arguments.
     *
     * @param array<string, mixed> $arguments
     *
     * @return void
     */
    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }

    /**
     * Get the advice type of the invocation.
     *
     * @return AdviceType
     *
     * @noinspection PhpUndefinedClassInspection
     */
    public function getAdviceType(): AdviceType
    {
        return match (true) {
            $this instanceof AroundMethodInvocation         => AdviceType::Around,
            $this instanceof BeforeMethodInvocation         => AdviceType::Before,
            $this instanceof AfterMethodInvocation          => AdviceType::After,
            // TODO: Implement
            $this instanceof AfterReturningMethodInvocation => AdviceType::AfterReturning,
            // TODO: Implement
            $this instanceof AfterThrowingMethodInvocation  => AdviceType::AfterThrowing,
        };
    }

    /**
     * Get the original subject class of the invocation.
     *
     * If the subject is a static method, this will return {@see null}.
     *
     * @return object|null
     */
    public function getSubject(): ?object
    {
        return $this->subject;
    }

    /**
     * Get the original subject class name of the invocation.
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * Get the original subject method name of the invocation.
     *
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }
}
