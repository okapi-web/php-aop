<?php
/** @noinspection PhpInternalEntityUsedInspection */
namespace Okapi\Aop\Factory;

use Nette\PhpGenerator\Attribute;
use Nette\PhpGenerator\ClassLike;
use Nette\PhpGenerator\Factory;
use Nette\PhpGenerator\Helpers;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\Parameter;
use Nette\PhpGenerator\PromotedParameter;
use Okapi\CodeTransformer\Service\DI;
use Roave\BetterReflection\Reflection\ReflectionMethod;
use Roave\BetterReflection\Reflection\ReflectionParameter;

// TODO: docs
class PhpFactory
{
    /**
     * Build PhpGenerator method from reflection method.
     *
     * @param ReflectionMethod $reflectionMethod
     *
     * @return Method
     *
     * @see Factory::fromMethodReflection()
     */
    public function fromReflectionMethod(
        ReflectionMethod $reflectionMethod,
    ): Method {
        $method = new Method($reflectionMethod->getName());

        $method->setParameters(
            array_map(
                [$this, 'fromParameterReflection'],
                $reflectionMethod->getParameters(),
            ),
        );
        $method->setStatic($reflectionMethod->isStatic());
        $isInterface = $reflectionMethod->getDeclaringClass()->isInterface();
        $method->setVisibility(
            $isInterface ? null : $this->getVisibility($reflectionMethod),
        );
        $method->setFinal($reflectionMethod->isFinal());
        $method->setAbstract($reflectionMethod->isAbstract());
        $method->setReturnReference($reflectionMethod->returnsReference());
        $method->setVariadic($reflectionMethod->isVariadic());
        /** @noinspection PhpInternalEntityUsedInspection */
        $method->setComment(
            Helpers::unformatDocComment((string)$reflectionMethod->getDocComment()),
        );
        $method->setAttributes($this->getAttributes($reflectionMethod));
        $method->setReturnType((string)$reflectionMethod->getReturnType());

        return $method;
    }

    /**
     * Build PhpGenerator property from reflection parameter.
     *
     * @param ReflectionParameter $reflectionParameter
     *
     * @return Parameter
     *
     * @see Factory::fromParameterReflection()
     */
    private function fromParameterReflection(
        ReflectionParameter $reflectionParameter,
    ): Parameter {
        $param = $reflectionParameter->isPromoted()
            ? new PromotedParameter($reflectionParameter->getName())
            : new Parameter($reflectionParameter->getName());
        $param->setReference($reflectionParameter->isPassedByReference());
        $param->setType((string)$reflectionParameter->getType());

        if ($reflectionParameter->isDefaultValueAvailable()) {
            if ($reflectionParameter->isDefaultValueConstant()) {
                $parts = explode(
                    '::',
                    $reflectionParameter->getDefaultValueConstantName(),
                );
                if (count($parts) > 1) {
                    $parts[0] = Helpers::tagName($parts[0]);
                }

                $param->setDefaultValue(new Literal(implode('::', $parts)));
            } else {
                $param->setDefaultValue($reflectionParameter->getDefaultValue());
            }

            $param->setNullable(
                $param->isNullable() && $param->getDefaultValue() !== null,
            );
        }

        $param->setAttributes($this->getAttributes($reflectionParameter));
        return $param;
    }

    /**
     * Get attributes from reflection parameter.
     *
     * @param ReflectionParameter|ReflectionMethod $reflection
     *
     * @return Attribute[]
     *
     * @see Factory::getAttributes()
     */
    private function getAttributes(
        ReflectionParameter|ReflectionMethod $reflection,
    ): array {
        static $factory;

        $attributes = [];

        foreach ($reflection->getAttributes() as $attribute) {
            $arguments = $attribute->getArguments();
            foreach ($arguments as &$argument) {
                if (is_object($argument)) {
                    if (!isset($factory)) {
                        $factory = DI::get(Factory::class);
                    }

                    $argument = $factory->fromObject($argument);
                }
            }

            $attributes[] = new Attribute($attribute->getName(), $arguments);
        }

        return $attributes;
    }

    /**
     * Get visibility from reflection method.
     *
     * @param ReflectionMethod $reflectionMethod
     *
     * @return string
     *
     * @see Factory::getVisibility()
     */
    private function getVisibility(ReflectionMethod $reflectionMethod): string
    {
        return $reflectionMethod->isPrivate()
            ? ClassLike::VisibilityPrivate
            : ($reflectionMethod->isProtected()
                ? ClassLike::VisibilityProtected
                : ClassLike::VisibilityPublic);
    }
}
