<?php

namespace Okapi\Aop\Container;

// TODO docs
abstract class AdviceContainer
{
    // TODO docs
    public function __construct(
        public string $filePath,
    ) {}

    // TODO docs
    abstract public function getName(): string;
}
