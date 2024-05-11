<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\AdviceBehavior\Readonly\Aspect;

use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Attributes\Aspect;

#[Aspect]
class ReadonlyAspect
{
    #[Around(
        class: 'Okapi\Aop\Tests\Functional\AdviceBehavior\Readonly\Target\Readonly*',
        method: '*',
    )]
    public function doNothing(): void {}
}
