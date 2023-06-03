<?php

namespace Okapi\Aop\Core\JoinPoint;

/**
 * # Join Point
 *
 * This class is responsible for storing the join point information.
 */
class JoinPoint
{
    /**
     * The join point parameter name.
     */
    public const JOIN_POINTS_PARAMETER_NAME = '__joinPoints';

    /**
     * The join point type.
     */
    public const TYPE_METHOD = 'method';
}
