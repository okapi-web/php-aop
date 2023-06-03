<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Stubs\Aspect\AdviceMatchingMultipleClassesAndMethods;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Tests\Stubs\ClassesToIntercept\AdviceMatchingMultipleClassesAndMethods\Order;
use Okapi\Aop\Tests\Stubs\ClassesToIntercept\AdviceMatchingMultipleClassesAndMethods\Product;

#[Aspect]
class DiscountAspect
{
    #[After(
        class: Product::class . '|' . Order::class,
        method: '*',
    )]
    public function applyDiscount(AfterMethodInvocation $invocation): void
    {
        $subject = $invocation->getSubject();

        $productDiscount = 0.1;
        $orderDiscount   = 0.2;

        if ($subject instanceof Product) {
            $oldPrice = $invocation->proceed();
            $newPrice = $oldPrice - ($oldPrice * $productDiscount);

            $invocation->setResult($newPrice);
        }

        if ($subject instanceof Order) {
            $oldTotal = $invocation->proceed();
            $newTotal = $oldTotal - ($oldTotal * $orderDiscount);

            $invocation->setResult($newTotal);
        }
    }
}
