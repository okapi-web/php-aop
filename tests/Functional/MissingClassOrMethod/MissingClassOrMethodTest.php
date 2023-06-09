<?php
/** @noinspection PhpExpressionResultUnusedInspection */
namespace Okapi\Aop\Tests\Functional\MissingClassOrMethod;

use Exception;
use Okapi\Aop\Core\Exception\Advice\MissingClassNameException;
use Okapi\Aop\Core\Exception\Advice\MissingMethodNameException;
use Okapi\Aop\Tests\Functional\MissingClassOrMethod\Aspect\AddItemLoggerAspect;
use Okapi\Aop\Tests\Functional\MissingClassOrMethod\Aspect\GetQuantityLoggerAspect;
use Okapi\Aop\Tests\Functional\MissingClassOrMethod\Aspect\RemoveItemLoggerAspect;
use Okapi\Aop\Tests\Functional\MissingClassOrMethod\ClassesToIntercept\InventoryManager;
use Okapi\Aop\Tests\Stubs\Kernel\MissingClassOrMethod\AddItemKernel;
use Okapi\Aop\Tests\Stubs\Kernel\MissingClassOrMethod\GetQuantityKernel;
use Okapi\Aop\Tests\Stubs\Kernel\MissingClassOrMethod\RemoveItemKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class MissingClassOrMethodTest extends TestCase
{
    /**
     * @see AddItemLoggerAspect::logAddItem()
     */
    public function testMissingClassName(): void
    {
        Util::clearCache();

        $error = null;

        try {
            AddItemKernel::init();
            new InventoryManager();
        } catch (Exception $e) {
            $error = $e;
        }

        $this->assertInstanceOf(
            MissingClassNameException::class,
            $error,
        );
    }

    /**
     * @see RemoveItemLoggerAspect::logRemoveItem()
     */
    public function testMissingMethodName(): void
    {
        Util::clearCache();

        $error = null;

        try {
            RemoveItemKernel::init();
            new InventoryManager();
        } catch (Exception $e) {
            $error = $e;
        }

        $this->assertInstanceOf(
            MissingMethodNameException::class,
            $error,
        );
    }

    /**
     * @see GetQuantityLoggerAspect::logGetQuantity()
     */
    public function testMissingClassAndMethodName(): void
    {
        Util::clearCache();

        $error = null;

        try {
            GetQuantityKernel::init();
            new InventoryManager();
        } catch (Exception $e) {
            $error = $e;
        }

        $missingClassNameException = $error instanceof MissingClassNameException;
        $missingMethodNameException = $error instanceof MissingMethodNameException;

        $this->assertTrue(
            $missingClassNameException || $missingMethodNameException,
        );
    }
}
