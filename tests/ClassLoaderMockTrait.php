<?php

namespace Okapi\Aop\Tests;

use Okapi\Aop\Core\AutoloadInterceptor\ClassLoader;
use Okapi\CodeTransformer\Core\CachedStreamFilter;
use Okapi\CodeTransformer\Core\StreamFilter;
use Okapi\CodeTransformer\Core\StreamFilter\FilterInjector;
use Okapi\Path\Path;
use PHPUnit\Framework\Assert;
use ReflectionProperty;

trait ClassLoaderMockTrait
{
    private ?ClassLoader $classLoader = null;

    private function findClassMock(string $class): string
    {
        if (!isset($this->classLoader)) {
            $this->findClassLoader();
        }

        Assert::assertInstanceOf(ClassLoader::class, $this->classLoader);
        /** @var mixed $file */
        $file = $this->classLoader->findFile($class);
        Assert::assertIsString($file);
        return $file;
    }

    private function findOriginalClassMock(string $class): string
    {
        if (!isset($this->classLoader)) {
            $this->findClassLoader();
        }

        Assert::assertInstanceOf(ClassLoader::class, $this->classLoader);
        $original = new ReflectionProperty(ClassLoader::class, 'originalClassLoader');
        /** @var mixed $loader */
        $loader = $original->getValue($this->classLoader);
        Assert::assertInstanceOf(\Composer\Autoload\ClassLoader::class, $loader);
        $file = $loader->findFile($class);
        Assert::assertIsString($file);
        return $file;
    }

    private function findClassLoader(): void
    {
        foreach (spl_autoload_functions() as $function) {
            if (!(is_array($function) && $function[0] instanceof ClassLoader)) {
                continue;
            }

            $this->classLoader = $function[0];
            break;
        }
    }

    public function assertWillBeWoven(string $className): void
    {
        $originalFilePath = Path::resolve($this->findOriginalClassMock($className));
        Assert::assertIsString($originalFilePath);

        $wovenPath = FilterInjector::PHP_FILTER_READ . StreamFilter::FILTER_ID . '/resource=' . $originalFilePath;

        $filePathMock = $this->findClassMock($className);

        Assert::assertEquals($wovenPath, $filePathMock, "{$className} will not be woven");
    }

    public function assertAspectLoadedFromCache(string $className): void
    {
        $filePath = Path::resolve($this->findOriginalClassMock($className));
        Assert::assertIsString($filePath);

        $cachePath = FilterInjector::PHP_FILTER_READ . CachedStreamFilter::CACHED_FILTER_ID . '/resource=' . $filePath;

        $filePathMock = $this->findClassMock($className);

        Assert::assertEquals($cachePath, $filePathMock, "{$className} will not be loaded from cache");
    }

    public function assertAspectNotApplied(string $className): void
    {
        $originalFilePath = Path::resolve($this->findOriginalClassMock($className));
        Assert::assertIsString($originalFilePath);
        $filePathMock = $this->findClassMock($className);

        Assert::assertEquals($originalFilePath, $filePathMock, "{$className} will be woven");
    }
}
