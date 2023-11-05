<?php

namespace Okapi\Aop\Tests\Functional\AspectMatching\AdviceMatchingMultipleClassesAndMethods;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AspectMatching\AdviceMatchingMultipleClassesAndMethods\Aspect\DiscountAspect;
use Okapi\Aop\Tests\Functional\AspectMatching\AdviceMatchingMultipleClassesAndMethods\Target\Order;
use Okapi\Aop\Tests\Functional\AspectMatching\AdviceMatchingMultipleClassesAndMethods\Target\Product;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class AdviceMatchingMultipleClassesAndMethodsTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see DiscountAspect::applyDiscount()
     */
    public function testAdviceMatchingMultipleClassesAndMethods(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(Product::class);
        $product = new Product();
        $productPrice = $product->getPrice();
        $this->assertEquals(90.00, $productPrice);

        $this->assertWillBeWoven(Order::class);
        $order = new Order();
        $orderTotal = $order->getTotal();
        $this->assertEquals(400.00, $orderTotal);
    }

    public function testCachedAdviceMatchingMultipleClassesAndMethods(): void
    {
        Kernel::init();

        $this->assertAspectLoadedFromCache(Product::class);
        $product = new Product();
        $productPrice = $product->getPrice();
        $this->assertEquals(90.00, $productPrice);

        $this->assertAspectLoadedFromCache(Order::class);
        $order = new Order();
        $orderTotal = $order->getTotal();
        $this->assertEquals(400.00, $orderTotal);
    }
}
