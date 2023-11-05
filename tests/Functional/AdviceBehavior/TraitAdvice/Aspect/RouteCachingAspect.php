<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\AdviceBehavior\TraitAdvice\Aspect;

use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AroundMethodInvocation;
use Okapi\Aop\Tests\Functional\AdviceBehavior\TraitAdvice\Target\RouteCaching;

#[Aspect]
class RouteCachingAspect
{
    public static array $cachedRoutes = [];

    #[Around(
        class: RouteCaching::class,
        method: 'getRoutes',
    )]
    public function cacheRoutes(AroundMethodInvocation $invocation): void
    {
        $arguments = $invocation->getArguments();

        $cacheKey = md5(serialize($arguments));

        $cachedRoutes = $this->getFromCache($cacheKey);
        if ($cachedRoutes) {
            $invocation->setResult($cachedRoutes);
            return;
        }

        $routes = $invocation->proceed();

        $this->storeInCache($cacheKey, $routes);
    }

    private function getFromCache(string $cacheKey): ?array
    {
        return self::$cachedRoutes[$cacheKey] ?? null;
    }

    private function storeInCache(string $cacheKey, array $routes): void
    {
        self::$cachedRoutes[$cacheKey] = $routes;
    }
}
