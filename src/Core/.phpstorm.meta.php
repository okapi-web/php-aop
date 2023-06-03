<?php

use Okapi\Aop\Core\Transformer\NetteReflectionWithBetterReflection;

/**
 * @see NetteReflectionWithBetterReflection
 */
namespace Nette\PhpGenerator {

    use Closure;
    use Roave\BetterReflection\Reflection\ReflectionClass as BetterReflectionClass;
    use Roave\BetterReflection\Reflection\ReflectionClassConstant as BetterReflectionClassConstant;
    use Roave\BetterReflection\Reflection\ReflectionFunction as BetterReflectionFunction;
    use Roave\BetterReflection\Reflection\ReflectionMethod as BetterReflectionMethod;
    use Roave\BetterReflection\Reflection\ReflectionParameter as BetterReflectionParameter;
    use Roave\BetterReflection\Reflection\ReflectionProperty as BetterReflectionProperty;

    class Factory
    {
        public function fromClassReflection(
            \ReflectionClass|BetterReflectionClass $from,
            bool                                   $withBodies = false,
            ?bool                                  $materializeTraits = null,
        ): ClassLike;

        public function fromMethodReflection(
            \ReflectionMethod|BetterReflectionMethod $from,
        ): Method;

        public function fromFunctionReflection(
            \ReflectionFunction|BetterReflectionFunction $from,
            bool $withBody = false,
        ): GlobalFunction|Closure;

        public function fromParameterReflection(
            \ReflectionParameter|BetterReflectionParameter $from,
        ): Parameter;

        public function fromConstantReflection(
            \ReflectionClassConstant|BetterReflectionClassConstant $from,
        ): Constant;

        public function fromCaseReflection(
            \ReflectionClassConstant|BetterReflectionClassConstant $from,
        ): EnumCase;

        public function fromPropertyReflection(
            \ReflectionProperty|BetterReflectionProperty $from,
        ): Property;
    }
}
