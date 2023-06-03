<?php
/** @noinspection PhpInternalEntityUsedInspection */
namespace Okapi\Aop\Core\Container;

use Okapi\Aop\Core\Attributes\Base\BaseAdvice;
use ReflectionAttribute as BaseReflectionAttribute;
use ReflectionClass as BaseReflectionClass;

/**
 * # Advice Container
 *
 * This class is used to store information about an advice.
 */
abstract class AdviceContainer
{
    /**
     * AdviceContainer constructor.
     *
     * @param string                  $aspectClassName
     * @param object                  $aspectInstance
     * @param BaseReflectionClass     $aspectRefClass
     * @param BaseReflectionAttribute $adviceAttribute
     * @param BaseAdvice              $adviceAttributeInstance
     */
    public function __construct(
        protected readonly string                  $aspectClassName,
        public readonly object                     $aspectInstance,
        public readonly BaseReflectionClass        $aspectRefClass,
        protected readonly BaseReflectionAttribute $adviceAttribute,
        public readonly BaseAdvice                 $adviceAttributeInstance,
    ) {}

    /**
     * Get the name of the advice.
     *
     * @return string
     */
    abstract public function getName(): string;
}
