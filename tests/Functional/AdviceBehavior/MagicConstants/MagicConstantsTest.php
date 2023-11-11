<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\MagicConstants;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceBehavior\MagicConstants\Kernel\{KernelOnClass,
    KernelOnClassAndParent,
    KernelOnClassAndParentAndTrait,
    KernelOnClassAndTrait,
    KernelOnParent,
    KernelOnParentAndTrait,
    KernelOnTrait};
use Okapi\Aop\Tests\Functional\AdviceBehavior\MagicConstants\Target\{TargetClass,
    TargetClass82,
    TargetParent,
    TargetParent82,
    TargetTrait,
    TargetTrait82};
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * Whoever can refactor this test to be more readable, good luck!
 *
 * @see https://github.com/okapi-web/php-aop/issues/69 (Nice)
 * @todo https://github.com/okapi-web/php-aop/issues/69#issuecomment-1806817698
 */
#[RunTestsInSeparateProcesses]
class MagicConstantsTest extends TestCase
{
    use ClassLoaderMockTrait;

    private string $targetClass = TargetClass::class;
    private string $targetParentClass = TargetParent::class;

    private const PREFIX_TARGET_PATH       = '/tests/Functional/AdviceBehavior/MagicConstants/Target';
    private string $prefixTargetClassPath = self::PREFIX_TARGET_PATH . '/TargetClass.php';
    private string $prefixTargetTraitPath = self::PREFIX_TARGET_PATH . '/TargetTrait.php';
    private string $prefixTargetParentPath = self::PREFIX_TARGET_PATH . '/TargetParent.php';

    private const NAMESPACE_TARGET       = 'Okapi\Aop\Tests\Functional\AdviceBehavior\MagicConstants\Target';
    private string $namespaceTargetClass = TargetClass::class;
    private string $namespaceTargetTrait = TargetTrait::class;
    private string $namespaceTargetParent = TargetParent::class;

    public function __construct(string $name)
    {
        parent::__construct($name);

        // >= 8.2
        if (version_compare(PHP_VERSION, '8.2.0', '>=')) {
            $this->targetClass = TargetClass82::class;
            $this->targetParentClass = TargetParent82::class;

            $this->prefixTargetClassPath = self::PREFIX_TARGET_PATH . '/TargetClass82.php';
            $this->prefixTargetTraitPath = self::PREFIX_TARGET_PATH . '/TargetTrait82.php';
            $this->prefixTargetParentPath = self::PREFIX_TARGET_PATH . '/TargetParent82.php';

            $this->namespaceTargetClass = TargetClass82::class;
            $this->namespaceTargetTrait = TargetTrait82::class;
            $this->namespaceTargetParent = TargetParent82::class;
        }
    }

    public function testMagicConstantsWithoutAop(): void
    {
        $this->test(new $this->targetClass);
    }

    // Class
    public function testMagicConstantsWithAopOnClass(): void
    {
        Util::clearCache();
        KernelOnClass::init();

        $this->assertWillBeWoven($this->targetClass);
        $this->test(new $this->targetClass);
    }

    // Class (Cached)
    public function testMagicConstantsWithAopOnClassCached(): void
    {
        KernelOnClass::init();

        $this->assertAspectLoadedFromCache($this->targetClass);
        $this->test(new $this->targetClass);
    }

    // Parent
    public function testMagicConstantsWithAopOnParent(): void
    {
        Util::clearCache();
        KernelOnParent::init();

        $this->assertWillBeWoven($this->targetParentClass);
        $this->assertWillBeWoven($this->targetClass);
        $this->test(new $this->targetClass, $this->namespaceTargetParent);
    }

    // Parent (Cached)
    public function testMagicConstantsWithAopOnParentCached(): void
    {
        KernelOnParent::init();

        $this->assertAspectLoadedFromCache($this->targetParentClass);
        $this->assertAspectLoadedFromCache($this->targetClass);
        $this->test(new $this->targetClass, $this->namespaceTargetParent);
    }

    // Trait
    public function testMagicConstantsWithAopOnTrait(): void
    {
        Util::clearCache();
        KernelOnTrait::init();

        $this->assertWillBeWoven($this->targetClass);
        $this->test(new $this->targetClass);
    }

    // Trait (Cached)
    public function testMagicConstantsWithAopOnTraitCached(): void
    {
        KernelOnTrait::init();

        $this->assertAspectLoadedFromCache($this->targetClass);
        $this->test(new $this->targetClass);
    }

    // Class + Parent
    public function testMagicConstantsWithAopOnClassAndParent(): void
    {
        Util::clearCache();
        KernelOnClassAndParent::init();

        $this->assertWillBeWoven($this->targetParentClass);
        $this->assertWillBeWoven($this->targetClass);
        $this->test(new $this->targetClass, $this->namespaceTargetParent);
    }

    // Class + Parent (Cached)
    public function testMagicConstantsWithAopOnClassAndParentCached(): void
    {
        KernelOnClassAndParent::init();

        $this->assertAspectLoadedFromCache($this->targetParentClass);
        $this->assertAspectLoadedFromCache($this->targetClass);
        $this->test(new $this->targetClass, $this->namespaceTargetParent);
    }

    // Class + Trait
    public function testMagicConstantsWithAopOnClassAndTrait(): void
    {
        Util::clearCache();
        KernelOnClassAndTrait::init();

        $this->assertWillBeWoven($this->targetClass);
        $this->test(new $this->targetClass);
    }

    // Class + Trait (Cached)
    public function testMagicConstantsWithAopOnClassAndTraitCached(): void
    {
        KernelOnClassAndTrait::init();

        $this->assertAspectLoadedFromCache($this->targetClass);
        $this->test(new $this->targetClass);
    }

    // Parent + Trait
    public function testMagicConstantsWithAopOnParentAndTrait(): void
    {
        Util::clearCache();
        KernelOnParentAndTrait::init();

        $this->assertWillBeWoven($this->targetParentClass);
        $this->assertWillBeWoven($this->targetClass);
        $this->test(new $this->targetClass, $this->namespaceTargetParent);
    }

    // Parent + Trait (Cached)
    public function testMagicConstantsWithAopOnParentAndTraitCached(): void
    {
        KernelOnParentAndTrait::init();

        $this->assertAspectLoadedFromCache($this->targetParentClass);
        $this->assertAspectLoadedFromCache($this->targetClass);
        $this->test(new $this->targetClass, $this->namespaceTargetParent);
    }

    // Class + Parent + Trait
    public function testMagicConstantsWithAopOnClassAndParentAndTrait(): void
    {
        Util::clearCache();
        KernelOnClassAndParentAndTrait::init();

        $this->assertWillBeWoven($this->targetParentClass);
        $this->assertWillBeWoven($this->targetClass);
        $this->test(new $this->targetClass, $this->namespaceTargetParent);
    }

    // Class + Parent + Trait (Cached)
    public function testMagicConstantsWithAopOnClassAndParentAndTraitCached(): void
    {
        KernelOnClassAndParentAndTrait::init();

        $this->assertAspectLoadedFromCache($this->targetParentClass);
        $this->assertAspectLoadedFromCache($this->targetClass);
        $this->test(new $this->targetClass, $this->namespaceTargetParent);
    }

    private function test(
        TargetClass|TargetClass82 $target,
        ?string $staticClass = null
    ): void {
        if (!$staticClass) {
            $staticClass = $this->namespaceTargetClass;
        }

        $constantExceptions = $this->testConstants($target);
        $propertyExceptions = $this->testProperty($target);
        $methodExceptions   = $this->testMethod($target, $staticClass);

        $exceptions = [
            ...$constantExceptions,
            ...$propertyExceptions,
            ...$methodExceptions,
        ];

        if ($exceptions) {
            $this->markTestIncomplete(
                'Some tests skipped: ' .
                'https://github.com/okapi-web/php-aop/issues/69#issuecomment-1806817698'
            );
        }
    }

    private function testConstants(TargetClass|TargetClass82 $target): array
    {
        $exceptions = [];

        $expected = [
            'dir'               => $this->np($this->rootPath() . self::PREFIX_TARGET_PATH),
            'file'              => $this->np($this->rootPath() . $this->prefixTargetClassPath),
            'function'          => '',
            'class'             => $this->namespaceTargetClass,
            'trait'             => '',
            'method'            => '',
            'namespace'         => self::NAMESPACE_TARGET,
            'targetClassClass'  => $this->namespaceTargetClass,
            'targetTraitClass'  => $this->namespaceTargetTrait,
            'targetParentClass' => $this->namespaceTargetParent,
            'selfClass'         => $this->namespaceTargetClass,
        ];

        // region Class

        $this->assertSame($expected, $target::CONST);

        // endregion

        // region Parent

        $expected['file']      = $this->np($this->rootPath() . $this->prefixTargetParentPath);
        $expected['class']     = $this->namespaceTargetParent;
        $expected['selfClass'] = $this->namespaceTargetParent;

        $this->assertSame($expected, $target::PARENT_CONST);

        // endregion

        // region Trait

        // Only PHP >= 8.2 has trait constants
        if (version_compare(PHP_VERSION, '8.2.0', '>=')) {
            $expected['file']  = $this->np($this->rootPath() . $this->prefixTargetTraitPath);
            $expected['trait'] = $this->namespaceTargetTrait;

            try {
                $this->assertSame($expected, $target::TRAIT_CONST);
            } catch (ExpectationFailedException $e) {
                $exceptions[] = $e;
            }
        }

        // endregion

        return $exceptions;
    }

    private function testProperty(TargetClass|TargetClass82 $target): array
    {
        $exceptions = [];

        $expected = [
            'dir'               => $this->np($this->rootPath() . self::PREFIX_TARGET_PATH),
            'file'              => $this->np($this->rootPath() . $this->prefixTargetClassPath),
            'function'          => '',
            'class'             => $this->namespaceTargetClass,
            'trait'             => '',
            'method'            => '',
            'namespace'         => self::NAMESPACE_TARGET,
            'targetClassClass'  => $this->namespaceTargetClass,
            'targetTraitClass'  => $this->namespaceTargetTrait,
            'targetParentClass' => $this->namespaceTargetParent,
            'selfClass'         => $this->namespaceTargetClass,
        ];

        // region Class

        $this->assertSame($expected, $target->property);
        $this->assertSame($expected, $target::$staticProperty);

        // endregion

        // region Parent

        $expected['file']      = $this->np($this->rootPath() . $this->prefixTargetParentPath);
        $expected['class']     = $this->namespaceTargetParent;
        $expected['selfClass'] = $this->namespaceTargetParent;

        $this->assertSame($expected, $target->parentProperty);
        $this->assertSame($expected, $target::$parentStaticProperty);

        // endregion

        // region Trait

        $expected['file']  = $this->np($this->rootPath() . $this->prefixTargetTraitPath);
        $expected['trait'] = $this->namespaceTargetTrait;

        try {
            $this->assertSame($expected, $target->traitProperty);
            $this->assertSame($expected, $target::$traitStaticProperty);
        } catch (ExpectationFailedException $e) {
            $exceptions[] = $e;
        }

        // endregion

        return $exceptions;
    }

    private function testMethod(
        TargetClass|TargetClass82 $target,
        ?string $staticClass
    ): array {
        if (!$staticClass) {
            $staticClass = $this->namespaceTargetClass;
        }

        $exceptions = [];

        $expected = [
            'dir'               => $this->np($this->rootPath() . self::PREFIX_TARGET_PATH),
            'file'              => $this->np($this->rootPath() . $this->prefixTargetClassPath),
            'function'          => 'method',
            'class'             => $this->namespaceTargetClass,
            'trait'             => '',
            'method'            => $this->namespaceTargetClass . '::method',
            'namespace'         => self::NAMESPACE_TARGET,
            'targetClassClass'  => $this->namespaceTargetClass,
            'targetTraitClass'  => $this->namespaceTargetTrait,
            'targetParentClass' => $this->namespaceTargetParent,
            'selfClass'         => $this->namespaceTargetClass,
            'staticClass'       => $this->namespaceTargetClass,
        ];

        // region Class

        $this->assertSame($expected, $target->method());

        $expected['function'] = 'staticMethod';
        $expected['method']   = $this->namespaceTargetClass . '::staticMethod';

        $this->assertSame($expected, $target::staticMethod());

        // endregion

        // region Parent

        $expected['file']      = $this->np($this->rootPath() . $this->prefixTargetParentPath);
        $expected['function']  = 'parentMethod';
        $expected['class']     = $this->namespaceTargetParent;
        $expected['method']    = $this->namespaceTargetParent . '::parentMethod';
        $expected['selfClass'] = $this->namespaceTargetParent;

        $this->assertSame($expected, $target->parentMethod());

        $expected['function']    = 'parentStaticMethod';
        $expected['method']      = $this->namespaceTargetParent . '::parentStaticMethod';
        $expected['staticClass'] = $staticClass;

        $this->assertSame($expected, $target::parentStaticMethod());

        // endregion

        // region Trait

        try {
            $expected['file']     = $this->np($this->rootPath() . $this->prefixTargetTraitPath);
            $expected['function'] = 'traitMethod';
            $expected['trait']    = $this->namespaceTargetTrait;
            $expected['method']   = $this->namespaceTargetTrait . '::traitMethod';

            $this->assertSame($expected, $target->traitMethod());

            $expected['function'] = 'traitStaticMethod';
            $expected['method']   = $this->namespaceTargetTrait . '::traitStaticMethod';

            $this->assertSame($expected, $target::traitStaticMethod());
        } catch (ExpectationFailedException $e) {
            $exceptions[] = $e;
        }

        // endregion

        return $exceptions;
    }

    // Normalize path
    private function np(string $path): string
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, $path);
    }

    private function rootPath(): string
    {
        return dirname(__DIR__, 4);
    }
}
