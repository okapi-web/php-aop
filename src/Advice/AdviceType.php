<?php

namespace Okapi\Aop\Advice;

/**
 * # Advice type
 *
 * This class is used to define the type of advice.
 */
enum AdviceType
{
    case Before;
    case Around;
    case After;
    // Reserved for future after-returning advice support.
    case AfterReturning;
    case AfterThrowing;
}
