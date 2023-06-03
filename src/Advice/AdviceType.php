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
    // TODO: implement
    case AfterReturning;
    // TODO: implement
    case AfterThrowing;
}
