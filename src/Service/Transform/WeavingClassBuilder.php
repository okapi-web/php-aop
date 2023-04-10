<?php
/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Service\Transform;

use DI\Attribute\Inject;
use Nette\PhpGenerator\ClassLike;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Factory;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Property;
use Okapi\Aop\Container\MethodAdviceContainer;
use Okapi\Aop\Factory\PhpFactory;
use Okapi\Aop\Service\Cache\CachePaths;
use Okapi\CodeTransformer\Service\DI;
use Okapi\CodeTransformer\Service\StreamFilter\Metadata\Code;

/**
 * # Weaving Class Builder
 *
 * The `WeavingClassBuilder` class is used to build weaving classes.
 */
class WeavingClassBuilder
{
    // region DI

    #[Inject]
    private CachePaths $cachePaths;

    #[Inject]
    private PhpFactory $phpFactory;

    // endregion

    /**
     * Name of the join points parameter.
     */
    public const JOIN_POINTS_PARAMETER_NAME = '__joinPoints';

    public const JOIN_POINT_METHOD = 'method';

    /**
     * The list of interception advices.
     *
     * @var MethodAdviceContainer[]
     */
    private array $methodAdviceContainers = [];

    /**
     * WeavingClassBuilder constructor.
     *
     * @param Code $code
     */
    public function __construct(
        private readonly Code $code,
    ) {}

    /**
     * Add an advice to the weaving class.
     *
     * @param MethodAdviceContainer $methodAdviceContainer
     *
     * @return void
     */
    public function addMethodAdviceContainer(MethodAdviceContainer $methodAdviceContainer): void
    {
        $this->methodAdviceContainers[] = $methodAdviceContainer;
    }

    // region Build

    /**
     * Build the weaving class.
     *
     * @return string
     */
    public function build(): string
    {
        // Build the namespace
        $phpNamespace = $this->buildNamespace();

        // Build the class
        $class = $this->buildClass($phpNamespace);

        // Build the join points
        $class->addMember($this->buildJoinPoints());

        // Build the methods
        $methods = $this->buildMethods();
        foreach ($methods as $method) {
            $class->addMember($method);
        }

        // Add the class to the namespace
        $phpNamespace->add($class);

        // Build the file
        $file = (string)$phpNamespace;

        $debug = "<?php\n\n" . $file;

        return "<?php\n\n" . $file;
    }

    /**
     * Build the namespace.
     *
     * @return PhpNamespace
     */
    private function buildNamespace(): PhpNamespace
    {
        $reflectionClass = $this->code->getReflectionClass();
        return new PhpNamespace($reflectionClass->getNamespaceName());
    }

    /**
     * Build the class.
     *
     * @param PhpNamespace $phpNamespace
     *
     * @return ClassType
     */
    private function buildClass(PhpNamespace $phpNamespace): ClassType
    {
        $class = new ClassType();

        $reflectionClass = $this->code->getReflectionClass();

        // Set the class name
        $shortClassName = $reflectionClass->getShortName();
        $class->setName($shortClassName);

        // Add the use statement
        $className = $reflectionClass->getName();
        $proxyClassName  = $className . $this->cachePaths->proxiedSuffix;
        $phpNamespace->addUse($proxyClassName);

        // Set the class extends
        $class->setExtends($proxyClassName);

        return $class;
    }

    /**
     * Build the join points.
     *
     * @return Property
     */
    private function buildJoinPoints(): Property
    {
        // Build property
        $property = new Property(static::JOIN_POINTS_PARAMETER_NAME);
        $property->setVisibility(ClassLike::VisibilityPrivate);
        $property->setStatic();
        $property->setType('array');

        // Build value
        $value = [];

        // Add interceptors
        foreach ($this->methodAdviceContainers as $methodAdviceContainer) {
            $refMethod = $methodAdviceContainer->refMethod;
            $value[self::JOIN_POINT_METHOD][$refMethod->getName()][] = "test";
        }

        $property->setValue($value);

        return $property;
    }

    /**
     * Build the methods.
     *
     * @return Method[]
     */
    private function buildMethods(): array
    {
        $methods = [];

        foreach ($this->methodAdviceContainers as $methodAdviceContainer) {
            $methods[] = $this->buildMethod($methodAdviceContainer);
        }

        return $methods;
    }

    // TODO: docs
    private function buildMethod(
        MethodAdviceContainer $methodAdviceContainer
    ): Method {
        $refMethod = $methodAdviceContainer->refMethod;
        $method = $this->phpFactory->fromReflectionMethod($refMethod);

        $return = $method->getReturnType() !== 'void' ? 'return ' : '';
        $body = $return . '$this->' . self::JOIN_POINTS_PARAMETER_NAME . '["method"]["' . $refMethod->getName() . '"]();';

        // $method->setBody('')

        return $method;
    }

    // endregion
}
