<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\TraitAdvice\Target;

trait RouteCaching
{
    public function getRoutes(): array
    {
        return [
            'GET' => ['/users', 'UserController@index'],
        ];
    }
}
