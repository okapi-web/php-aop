<?php

namespace Okapi\Aop\Tests\Stubs\ClassesToIntercept\TraitAdvice;

trait RouteCaching
{
    public function getRoutes(): array
    {
        return [
            'GET' => ['/users', 'UserController@index'],
        ];
    }
}
