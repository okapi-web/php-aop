<?php
/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Core\Container;

use DI\Attribute\Inject;
use Error;
use Exception;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Core\Attributes\Base\BaseAdvice;
use Okapi\Aop\Core\Exception\Aspect\AspectNotFoundException;
use Okapi\Aop\Core\Exception\Aspect\InvalidAspectClassNameException;
use Okapi\Aop\Core\Exception\Aspect\MissingAspectAttributeException;
use Okapi\CodeTransformer\Core\DI;
use ReflectionAttribute as BaseReflectionAttribute;
use ReflectionClass as BaseReflectionClass;
use ReflectionMethod as BaseReflectionMethod;
use ReflectionProperty as BaseReflectionProperty;

/**
 * # Aspect Manager
 *
 * This class is used to register and manage the aspects.
 */
class AspectManager
{
    // region DI

    #[Inject]
    private AdviceContainerFactory $adviceContainerFactory;

    // endregion

    /**
     * The list of aspects class strings.
     *
     * @param class-string[] $aspects
     *
     * @return void
     */
    private array $aspects = [];

    /**
     * List of aspects with list of advices.
     *
     * @var array<class-string, AdviceContainer[]> Key is the aspect class string
     */
    private array $aspectAdviceContainers = [];

    /**
     * List of advice containers.
     *
     * @var array<string, AdviceContainer[]> Key is the advice container name
     */
    private array $adviceContainers = [];

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
        foreach ($this->aspects as $aspectClassName) {
            // Validate the aspect
            if (gettype($aspectClassName) !== 'string') {
                throw new InvalidAspectClassNameException;
            }

            // Instantiate the aspect
            try {
                $aspectInstance = DI::make($aspectClassName);
            } catch (Error|Exception) {
                throw new AspectNotFoundException($aspectClassName);
            }

            // Create a reflection of the aspect
            $aspectRefClass = new BaseReflectionClass($aspectInstance);

            // Validate the aspect attribute
            $attributes = $aspectRefClass->getAttributes(
                Aspect::class,
                BaseReflectionAttribute::IS_INSTANCEOF,
            );
            if (!$attributes) {
                throw new MissingAspectAttributeException($aspectClassName);
            }

            // Iterate over the aspect methods and properties
            $methods    = $aspectRefClass->getMethods();
            $properties = $aspectRefClass->getProperties();
            /** @var (BaseReflectionMethod|BaseReflectionProperty)[] $adviceRefMembers */
            $adviceRefMembers = array_merge($methods, $properties);
            foreach ($adviceRefMembers as $adviceRefMember) {
                // Get the advices
                $adviceAttributes = $adviceRefMember->getAttributes(
                    BaseAdvice::class,
                    BaseReflectionAttribute::IS_INSTANCEOF,
                );

                // Create advice containers and store them
                foreach ($adviceAttributes as $adviceAttribute) {
                    $adviceContainer = $this->adviceContainerFactory->createAdviceContainer(
                        $aspectClassName,
                        $aspectInstance,
                        $aspectRefClass,
                        $adviceAttribute,
                        $adviceRefMember,
                    );

                    $this->aspectAdviceContainers[$aspectClassName][]      = $adviceContainer;
                    $this->adviceContainers[$adviceContainer->getName()][] = $adviceContainer;
                }
            }
        }
    }

    // endregion

    /**
     * Get the aspects.
     *
     * @return class-string[]
     */
    public function getAspects(): array
    {
        return $this->aspects;
    }

    /**
     * Get the aspect advice containers.
     *
     * @return array<class-string, AdviceContainer[]>
     */
    public function getAspectAdviceContainers(): array
    {
        return $this->aspectAdviceContainers;
    }

    /**
     * Get the advice containers by advice names.
     *
     * @param string[] $adviceNames
     *
     * @return AdviceContainer[]
     */
    public function getAdviceContainersByAdviceNames(array $adviceNames): array
    {
        return array_reduce(
            $adviceNames,
            fn (array $carry, string $adviceName) => array_merge(
                $carry,
                $this->adviceContainers[$adviceName] ?? [],
            ),
            [],
        );
    }
}
