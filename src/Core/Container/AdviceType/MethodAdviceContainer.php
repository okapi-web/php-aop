<?php

namespace Okapi\Aop\Core\Container\AdviceType;

use Okapi\Aop\Core\Attributes\AdviceType\MethodAdvice;
use Okapi\Aop\Core\Container\AdviceContainer;
use Okapi\Aop\Core\Matcher\AdviceMatcher\MatchedMethod;
use Okapi\CodeTransformer\Core\DI;
use ReflectionAttribute as BaseReflectionAttribute;
use ReflectionClass as BaseReflectionClass;
use ReflectionMethod as BaseReflectionMethod;
use Roave\BetterReflection\Reflection\ReflectionMethod as BetterReflectionMethod;

/**
 * # Method Advice Container
 *
 * This class is used to store method advice information.
 *
 * @property-read MethodAdvice $adviceAttributeInstance
 */
class MethodAdviceContainer extends AdviceContainer
{
    /**
     * List of matched methods.
     *
     * @var MatchedMethod[]
     */
    private array $matchedMethods = [];

    /**
     * MethodAdviceContainer constructor.
     *
     * @param class-string            $aspectClassName
     * @param object                  $aspectInstance
     * @param BaseReflectionClass     $aspectRefClass
     * @param BaseReflectionAttribute $adviceAttribute
     * @param MethodAdvice            $adviceAttributeInstance
     * @param BaseReflectionMethod    $adviceRefMethod
     * @param bool                    $isExplicit
     */
    public function __construct(
        string                               $aspectClassName,
        object                               $aspectInstance,
        BaseReflectionClass                  $aspectRefClass,
        BaseReflectionAttribute              $adviceAttribute,
        MethodAdvice                         $adviceAttributeInstance,
        public readonly BaseReflectionMethod $adviceRefMethod,
        private readonly bool                $isExplicit,
    ) {
        parent::__construct(
            $aspectClassName,
            $aspectInstance,
            $aspectRefClass,
            $adviceAttribute,
            $adviceAttributeInstance,
        );
    }

    /**
     * Add matched method.
     *
     * @param BetterReflectionMethod $matchedRefMethod
     *
     * @return void
     */
    public function addMatchedMethod(
        BetterReflectionMethod $matchedRefMethod,
    ): void {
        $this->matchedMethods[] = DI::make(MatchedMethod::class, [
            'matchedRefMethod' => $matchedRefMethod,
        ]);
    }

    /**
     * Get matched methods.
     *
     * @return MatchedMethod[]
     */
    public function getMatchedMethods(): array
    {
        return $this->matchedMethods;
    }

    /**
     * Get advice name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->aspectClassName . '::' . $this->adviceRefMethod->getName();
    }

    /**
     * Is explicit.
     *
     * @return bool
     */
    public function isExplicit(): bool
    {
        return $this->isExplicit;
    }
}
