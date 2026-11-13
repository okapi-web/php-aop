<?php

namespace Okapi\Aop\Attributes;

use Attribute;
use InvalidArgumentException;
use Okapi\Aop\Core\Attributes\AdviceType\MethodAdvice;
use Okapi\Wildcards\Regex;
use Throwable;

/**
 * Run advice when the target method throws.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class AfterThrowing extends MethodAdvice
{
    /**
     * @var class-string<Throwable>
     */
    public readonly string $exception;

    /**
     * @param string $exception The Throwable class or interface to match,
     *                          including subclasses.
     */
    public function __construct(
        string|Regex|null $class = null,
        string|Regex|null $method = null,
        int $order = 0,
        bool $interceptTraitMethods = true,
        bool $onlyPublicMethods = false,
        string $exception = Throwable::class,
    ) {
        parent::__construct($class, $method, $order, $interceptTraitMethods, $onlyPublicMethods);

        if (!is_a($exception, Throwable::class, true)) {
            throw new InvalidArgumentException(
                'Invalid exception filter "' . $exception . '": expected a Throwable class or interface.',
            );
        }

        $this->exception = $exception;
    }
}
