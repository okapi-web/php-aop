<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Stubs\Aspect\InterfaceAdvice;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Tests\Stubs\ClassesToIntercept\InterfaceAdvice\UserInterface;

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
