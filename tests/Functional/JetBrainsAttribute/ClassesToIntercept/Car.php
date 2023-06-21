<?php

namespace Okapi\Aop\Tests\Functional\JetBrainsAttribute\ClassesToIntercept;

use JetBrains\PhpStorm\Deprecated;

class Car
{
    #[Deprecated]
    public function startCar(): void
    {
        echo 'Car started';
    }
}
