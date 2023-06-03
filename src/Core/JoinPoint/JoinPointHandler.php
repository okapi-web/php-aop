<?php
/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Core\JoinPoint;

use DI\Attribute\Inject;
use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Attributes\Before;
use Okapi\Aop\Core\Matcher\AspectMatcher;

/**
 * # Join Point Handler
 *
 * This class is used to handle the join points for the given class.
 */
class JoinPointHandler
{
    // region DI

    #[Inject]
    private AspectMatcher $aspectMatcher;

    // endregion

    /**
     * JoinPointHandler constructor.
     *
     * @param class-string $className
     * @param string[]     $joinPoints
     */
    public function __construct(
        private readonly string $className,
        private readonly array  $joinPoints,
    ) {}

    /**
     * Add the matched advices to the given interceptor arrays.
     *
     * @param array $beforeInterceptors
     * @param array $aroundInterceptors
     * @param array $afterInterceptors
     *
     * @return void
     */
    public function handle(
        array &$beforeInterceptors,
        array &$aroundInterceptors,
        array &$afterInterceptors,
    ): void {
        foreach ($this->joinPoints as $joinPoint) {
            $adviceContainers = $this->aspectMatcher->getMatchedAdviceContainersByJoinPoint(
                $this->className,
                $joinPoint,
            );

            foreach ($adviceContainers as $adviceContainer) {
                $adviceAttributeInstance = $adviceContainer->adviceAttributeInstance;
                switch (true) {
                    case $adviceAttributeInstance instanceof Before:
                        $beforeInterceptors[] = $adviceContainer;
                        break;
                    case $adviceAttributeInstance instanceof Around:
                        $aroundInterceptors[] = $adviceContainer;
                        break;
                    case $adviceAttributeInstance instanceof After:
                        $afterInterceptors[] = $adviceContainer;
                        break;
                }
            }
        }
    }
}
