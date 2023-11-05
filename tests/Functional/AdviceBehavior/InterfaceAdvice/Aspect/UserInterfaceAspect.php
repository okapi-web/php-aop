<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\AdviceBehavior\InterfaceAdvice\Aspect;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Tests\Functional\AdviceBehavior\InterfaceAdvice\Target\UserInterface;

#[Aspect]
class UserInterfaceAspect
{
    #[After(
        class: UserInterface::class,
        method: 'getName',
    )]
    public function modifyName(): string
    {
        return 'Jane Doe';
    }
}
