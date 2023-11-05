<?php

namespace Okapi\Aop\Tests\Functional\AspectMatching\JetBrainsAttribute\Target;

use JetBrains\PhpStorm\Deprecated;

class Car
{
    #[Deprecated]
    public function startCar(): void
    {
        echo 'Car started';
    }
}
