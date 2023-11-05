<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\TraitAdvice;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceBehavior\TraitAdvice\Aspect\RouteCachingAspect;
use Okapi\Aop\Tests\Functional\AdviceBehavior\TraitAdvice\Target\Router;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class TraitAdviceTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see RouteCachingAspect::cacheRoutes()
     */
    public function testTraitAdvice(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(Router::class);
        $router = new Router();

        $routes = $router->getRoutes();
        $this->assertCount(1, $routes);

        $cachedRoutes = RouteCachingAspect::$cachedRoutes;
        $this->assertCount(1, $cachedRoutes);
    }
}
