<?php
/** @noinspection PhpInternalEntityUsedInspection */
namespace Okapi\Aop\Core\Matcher;

use Okapi\Aop\Core\Container\AdviceContainer;
use Okapi\Aop\Core\Container\AdviceType\MethodAdviceContainer;
use Okapi\Wildcards\Regex;
use Roave\BetterReflection\Reflection\Reflection as BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass as BetterReflectionClass;
use Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;

/**
 * # Class Matcher
 *
 * This class is used to match a given class with the given advice container.
 */
class ClassMatcher
{
    /**
     * Match the given class with the given advice container.
     *
     * @param BetterReflectionClass $refClass
     * @param AdviceContainer       $adviceContainer
     * @param bool                  $explicitClassAspectTargets
     * @param bool                  $explicitMethodAspectTargets
     *
     * @return bool
     */
    public function match(
        BetterReflectionClass $refClass,
        AdviceContainer       $adviceContainer,
        bool                  $explicitClassAspectTargets,
        bool                  $explicitMethodAspectTargets,
    ): bool {
        // Check for explicit match
        /** @noinspection PhpConditionAlreadyCheckedInspection */
        if ($adviceContainer instanceof MethodAdviceContainer) {
            if ($adviceContainer->isExplicit()) {
                return $explicitClassAspectTargets || $explicitMethodAspectTargets;
            }
        }

        // Check for implicit match
        $adviceAttributeInstance = $adviceContainer->adviceAttributeInstance;
        $classRegex              = $adviceAttributeInstance->class;
        $namespacedClass         = $refClass->getName();

        $classMatches = $classRegex->matches($namespacedClass);

        $interfacesMatches = $this->matchInterfaces(
            $classRegex,
            $refClass,
        );

        $parentClassesMatches = $this->matchParentClasses(
            $classRegex,
            $refClass,
        );

        $traitsMatches = $this->matchTraits(
            $classRegex,
            $refClass,
        );

        return $classMatches
            || $interfacesMatches
            || $parentClassesMatches
            || $traitsMatches;
    }

    /**
     * Match the interfaces of the given class.
     *
     * @param Regex                 $classRegex
     * @param BetterReflectionClass $reflectionClass
     *
     * @return bool
     */
    protected function matchInterfaces(
        Regex                 $classRegex,
        BetterReflectionClass $reflectionClass,
    ): bool {
        return $this->matchType(
            'InterfaceNames',
            $classRegex,
            $reflectionClass,
        );
    }

    /**
     * Match the parent classes of the given class.
     *
     * @param Regex                 $classRegex
     * @param BetterReflectionClass $reflectionClass
     *
     * @return bool
     */
    protected function matchParentClasses(
        Regex                 $classRegex,
        BetterReflectionClass $reflectionClass,
    ): bool {
        return $this->matchType(
            'ParentClassNames',
            $classRegex,
            $reflectionClass,
        );
    }

    /**
     * Match the traits of the given class.
     *
     * @param Regex                 $classRegex
     * @param BetterReflectionClass $reflectionClass
     *
     * @return bool
     */
    protected function matchTraits(
        Regex                 $classRegex,
        BetterReflectionClass $reflectionClass,
    ): bool {
        return $this->matchType(
            'Traits',
            $classRegex,
            $reflectionClass,
        );
    }

    /**
     * Match the given type of the given class.
     *
     * @param string                $type
     * @param Regex                 $classRegex
     * @param BetterReflectionClass $reflectionClass
     *
     * @return bool
     */
    protected function matchType(
        string                $type,
        Regex                 $classRegex,
        BetterReflectionClass $reflectionClass,
    ): bool {
        try {
            $method = 'get' . $type;
            /** @var (BetterReflection|string)[] $reflections */
            $reflections = $reflectionClass->$method();
            foreach ($reflections as $reflection) {
                if ($classRegex->matches(
                    $reflection instanceof BetterReflection
                        ? $reflection->getName()
                        : $reflection,
                )) {
                    return true;
                }
            }
        } catch (IdentifierNotFound) {
            // Do nothing
        }

        return false;
    }
}
