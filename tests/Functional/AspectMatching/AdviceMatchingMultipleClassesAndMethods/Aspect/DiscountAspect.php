<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\AspectMatching\AdviceMatchingMultipleClassesAndMethods\Aspect;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Tests\Functional\AspectMatching\AdviceMatchingMultipleClassesAndMethods\Target\Order;
use Okapi\Aop\Tests\Functional\AspectMatching\AdviceMatchingMultipleClassesAndMethods\Target\Product;

#[Aspect]
class DiscountAspect
{
    #[After(
        class: Product::class . '|' . Order::class,
        method: 'get(Price|Total)',
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
