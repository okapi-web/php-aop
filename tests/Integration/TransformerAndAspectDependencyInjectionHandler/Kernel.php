<?php

namespace Okapi\Aop\Tests\Integration\TransformerAndAspectDependencyInjectionHandler;

use Closure;
use Okapi\Aop\AopKernel;
use Okapi\Aop\Component\ComponentType;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected function dependencyInjectionHandler(): ?Closure
    {
        return /** @param class-string $className */ static function (string $className, ComponentType $type) {
            /** @noinspection PhpIfWithCommonPartsInspection */
            if ($type === ComponentType::ASPECT) {
                echo 'Generating aspect instance: ' . $className . PHP_EOL;

                return (new \ReflectionClass($className))->newInstance();
            }
            echo 'Generating transformer instance: ' . $className . PHP_EOL;

            return (new \ReflectionClass($className))->newInstance();
        };
    }

    /** @var array<array-key, class-string> */
    protected array $aspects = [
        Aspect::class,
    ];

    /** @var array<array-key, mixed> */
    protected array $transformers = [
        Transformer::class,
    ];
}
