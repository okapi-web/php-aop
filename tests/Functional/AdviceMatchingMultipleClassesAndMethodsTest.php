<?php

namespace Okapi\Aop\Tests\Functional;

use Okapi\Aop\Tests\Stubs\Aspect\AdviceMatchingMultipleClassesAndMethods\DiscountAspect;
use Okapi\Aop\Tests\Stubs\ClassesToIntercept\AdviceMatchingMultipleClassesAndMethods\Order;
use Okapi\Aop\Tests\Stubs\ClassesToIntercept\AdviceMatchingMultipleClassesAndMethods\Product;
use Okapi\Aop\Tests\Stubs\Kernel\ApplicationKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class AdviceMatchingMultipleClassesAndMethodsTest extends TestCase
{
    /**
     * @see DiscountAspect::applyDiscount()
     */
    public function testAdviceMatchingMultipleClassesAndMethods(): void
    {
        Util::clearCache();
        ApplicationKernel::init();

        $product = new Product();
        $productPrice = $product->getPrice();
        $this->assertEquals(90.00, $productPrice);

        $order = new Order();
        $orderTotal = $order->getTotal();
        $this->assertEquals(400.00, $orderTotal);
    }

    public function testCachedAdviceMatchingMultipleClassesAndMethods(): void
    {
        ApplicationKernel::init();

        $product = new Product();
        $productPrice = $product->getPrice();
        $this->assertEquals(90.00, $productPrice);

        $order = new Order();
        $orderTotal = $order->getTotal();
        $this->assertEquals(400.00, $orderTotal);
    }
}
