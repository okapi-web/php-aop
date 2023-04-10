<?php

namespace Okapi\Aop\Container;

use Okapi\Aop\Attributes\Base\MethodAdvice;
use Roave\BetterReflection\Reflection\ReflectionMethod;

// TODO: docs
class MethodAdviceContainer extends AdviceContainer
{
    // TODO: docs
    public function __construct(
        string $filePath,
        public MethodAdvice $advice,
        public ReflectionMethod $refMethod,
    ) {
        parent::__construct($filePath);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->advice->name;
    }
}
