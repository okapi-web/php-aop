<?php
/** @noinspection PhpInternalEntityUsedInspection */
namespace Okapi\Aop\Core\Matcher;

use Okapi\Wildcards\Regex;
use Roave\BetterReflection\Reflection\Reflection as BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass as BetterReflectionClass;
use Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;

/**
 * # Class Matcher
 *
 * This class is used to match a given class with the given regex.
 */
class ClassMatcher
{
    /**
     * Match the interfaces of the given class.
     *
     * @param Regex                 $classRegex
     * @param BetterReflectionClass $reflectionClass
     *
     * @return bool
     */
    public function matchInterfaces(
        Regex                 $classRegex,
        BetterReflectionClass $reflectionClass,
    ): bool {
        return $this->match(
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
    public function matchParentClasses(
        Regex                 $classRegex,
        BetterReflectionClass $reflectionClass,
    ): bool {
        return $this->match(
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
    public function matchTraits(
        Regex                 $classRegex,
        BetterReflectionClass $reflectionClass,
    ): bool {
        return $this->match(
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
    private function match(
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
