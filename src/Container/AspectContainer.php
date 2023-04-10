<?php
/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Container;

use Error;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Attributes\Base\BaseAdvice;
use Okapi\Aop\Exception\Aspect\AspectNotFoundException;
use Okapi\Aop\Exception\Aspect\InvalidAspectClassNameException;
use Okapi\Aop\Exception\Aspect\MissingAspectAttributeException;
use Okapi\CodeTransformer\Service\TransformerContainer;
use ReflectionAttribute;
use ReflectionClass;

/**
 * # Aspect container
 *
 * The `AspectContainer` class is used to manage the aspects.
 */
class AspectContainer extends TransformerContainer
{
    /**
     * The list of aspects class strings.
     *
     * @param class-string[] $aspects
     *
     * @return void
     */
    private array $aspects = [];

    /**
     * List of advices.
     *
     * @var array{class-string, BaseAdvice[]}
     */
    private array $advices = [];

    // region Pre-Initialization

    /**
     * Add aspects.
     *
     * @param class-string[] $aspectClasses
     *
     * @return void
     */
    public function addAspects(array $aspectClasses): void
    {
        $this->aspects = array_merge(
            $this->aspects,
            $aspectClasses,
        );
    }

    // endregion

    // region Initialization

    /**
     * Register the aspect container.
     *
     * @return void
     */
    public function register(): void
    {
        $this->loadAspects();
    }

    /**
     * Load the aspects.
     *
     * @return void
     *
     * @noinspection PhpUnhandledExceptionInspection Handled by {@link AspectNotFoundException}
     * @noinspection PhpDocMissingThrowsInspection   Handled by {@link AspectNotFoundException}
     */
    private function loadAspects(): void
    {
        foreach ($this->aspects as $aspect) {
            // Validate the aspect
            if (gettype($aspect) !== 'string') {
                throw new InvalidAspectClassNameException;
            }

            // Instantiate the aspect
            try {
                $aspectInstance = new $aspect();
            } catch (Error) {
                throw new AspectNotFoundException($aspect);
            }

            // Create a reflection of the aspect
            $reflectionAspect = new ReflectionClass($aspectInstance);

            // Validate the aspect attribute
            $attributes = $reflectionAspect->getAttributes(
                Aspect::class,
                ReflectionAttribute::IS_INSTANCEOF,
            );
            if (!$attributes) {
                throw new MissingAspectAttributeException($aspect);
            }

            // Iterate over the aspect methods
            foreach ($reflectionAspect->getMethods() as $method) {
                // Get the advices
                $advices = $method->getAttributes(
                    BaseAdvice::class,
                    ReflectionAttribute::IS_INSTANCEOF,
                );

                // Instantiate and add the advices
                foreach ($advices as $advice) {
                    $this->advices[] = $advice->newInstance();
                }
            }
        }
    }

    // endregion

    /**
     * Get the advices.
     *
     * @return BaseAdvice[]
     */
    public function getAdvices(): array
    {
        return $this->advices;
    }
}
