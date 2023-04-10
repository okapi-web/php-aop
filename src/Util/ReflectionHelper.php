<?php

namespace Okapi\Aop\Util;

use Composer\Autoload\ClassLoader;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\ComposerSourceLocator;

// TODO: docs
class ReflectionHelper
{
    /**
     * Composer class loader.
     *
     * @var ClassLoader
     */
    private ClassLoader $classLoader;

    /**
     * Set class loader.
     *
     * @param ClassLoader $classLoader
     *
     * @return void
     */
    public function setClassLoader(ClassLoader $classLoader): void
    {
        $this->classLoader = $classLoader;
    }

    /**
     * Get reflection class.
     *
     * @param class-string $namespacedClass
     *
     * @return ReflectionClass
     */
    public function getReflectionClass(string $namespacedClass): ReflectionClass
    {
        static $astLocator, $reflector;

        if (!isset($astLocator, $reflector)) {
            $astLocator = (new BetterReflection())->astLocator();
            $reflector = new DefaultReflector(
                new ComposerSourceLocator($this->classLoader, $astLocator)
            );
        }

        return $reflector->reflectClass($namespacedClass);
    }
}
