<?php

namespace Okapi\Aop\Tests\Functional\TraitAdvice\ClassesToIntercept;

trait RouteCaching
{
    public function getRoutes(): array
    {
        return [
            'GET' => ['/users', 'UserController@index'],
        ];
    }
}
