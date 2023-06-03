<?php

namespace Okapi\Aop\Core\Transformer;

use Microsoft\PhpParser\Node;
use Microsoft\PhpParser\Node\DelimitedList\QualifiedNameList;
use Microsoft\PhpParser\Node\Expression\CallExpression;
use Microsoft\PhpParser\Node\Expression\MemberAccessExpression;
use Microsoft\PhpParser\Node\MethodDeclaration;
use Microsoft\PhpParser\Node\Statement\NamespaceUseDeclaration;
use Nette\PhpGenerator\Factory;
use Nette\Utils\Reflection;
use Okapi\CodeTransformer\Transformer;
use Okapi\CodeTransformer\Transformer\Code;

/**
 * # Nette Reflection With Better Reflection
 *
 * This transformer allows you to use the Nette Reflection API with the
 * Better Reflection library.
 */
class NetteReflectionWithBetterReflection extends Transformer
{
    /**
     * @inheritDoc
     */
    public function getTargetClass(): string|array
    {
        return [
            Factory::class,
            Reflection::class,
        ];
    }

    /**
     * @inheritDoc
     *
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection PhpUndefinedVariableInspection
     */
    public function transform(Code $code): void
    {
        $className        = $code->getClassName();
        $lastUseStatement = match ($className) {
            'Factory'    => 'use Nette\Utils\Reflection;',
            'Reflection' => 'use Nette;',
        };

        $sourceFileNode = $code->getSourceFileNode();

        foreach ($sourceFileNode->getDescendantNodes() as $node) {
            // Add BetterReflection use statements
            if ($node instanceof NamespaceUseDeclaration
                && $node->getText() === $lastUseStatement) {
                $base = "use Roave\BetterReflection\Reflection";
                $code->editAt(
                    $node->getEndPosition(),
                    0,
                    "$base\ReflectionClass as BetterReflectionClass;" .
                    "$base\ReflectionClassConstant as BetterReflectionClassConstant;" .
                    "$base\ReflectionFunction as BetterReflectionFunction;" .
                    "$base\ReflectionMethod as BetterReflectionMethod;" .
                    "$base\ReflectionParameter as BetterReflectionParameter;" .
                    "$base\ReflectionProperty as BetterReflectionProperty;",
                );
            }

            // Add BetterReflection to Reflection types as a union
            if ($node instanceof QualifiedNameList
                && (
                    ($class = ($node->getText() === '\ReflectionClass'))
                    || ($classConstant = ($node->getText() === '\ReflectionClassConstant'))
                    || ($function = ($node->getText() === '\ReflectionFunction'))
                    || ($method = ($node->getText() === '\ReflectionMethod'))
                    || ($parameter = ($node->getText() === '\ReflectionParameter'))
                    || ($property = ($node->getText() === '\ReflectionProperty'))
                )
            ) {
                $code->editAt(
                    $node->getEndPosition(),
                    0,
                    '|BetterReflection' . match (true) {
                        $class         => 'Class',
                        $classConstant => 'ClassConstant',
                        $function      => 'Function',
                        $method        => 'Method',
                        $parameter     => 'Parameter',
                        $property      => 'Property',
                    },
                );
            }

            // Replace Reflection::property with BetterReflection::getProperty()
            if ($node instanceof MemberAccessExpression
                || $node instanceof CallExpression
            ) {
                $text = $node->getText();
                if (($fromName = ($text === '$from->name'))
                    || ($fromParentName = ($text === '$from->getParentClass()->name'))
                    || ($declaringClassName = ($text === '$declaringClass->name'))
                    || ($methodName = ($text === '$method->name'))
                    || ($declaringMethodName = ($text === '$declaringMethod->name'))
                    || ($fromConstants = ($text === '$from->getReflectionConstants()'))
                    || ($constName = ($text === '$const->name'))
                    || ($declaringConstName = ($text === '$const->getDeclaringClass()->name'))
                ) {
                    $methodNode = $this->findParent(
                        $node,
                        MethodDeclaration::class,
                    );

                    // Get text between "function from" and "("
                    $start      = 'function from';
                    $end        = '(';
                    $methodText = $methodNode->getText();
                    $startPos   = strpos($methodText, $start);
                    $endPos     = strpos($methodText, $end);
                    if (!$startPos || !$endPos) {
                        continue;
                    }

                    // Prevent duplicate edits
                    static $alreadyAdded = [];
                    $key = "{$node->getStartPosition()}:{$node->getEndPosition()}";
                    if (isset($alreadyAdded[$key])) {
                        continue;
                    }
                    $alreadyAdded[$key] = true;

                    $methodText = substr(
                        $methodText,
                        $startPos + strlen($start),
                        $endPos - $startPos - strlen($start),
                    );

                    $betterReflectionType = match ($methodText) {
                        'Class', 'ClassReflection'             => 'BetterReflectionClass',
                        'CaseReflection', 'ConstantReflection' => 'BetterReflectionClassConstant',
                        'FunctionReflection'                   => 'BetterReflectionFunction',
                        'MethodReflection'                     => 'BetterReflectionMethod',
                        'ParameterReflection'                  => 'BetterReflectionParameter',
                        'PropertyReflection'                   => 'BetterReflectionProperty',
                    };

                    $code->edit(
                        $node,
                        match (true) {
                            $fromName            => "(\$from instanceof $betterReflectionType ? \$from->getName() : \$from->name)",
                            $fromParentName      => "(\$from->getParentClass() instanceof BetterReflectionClass ? \$from->getParentClass()->getName() : \$from->getParentClass()->name)",
                            $declaringClassName  => "(\$declaringClass instanceof BetterReflectionClass ? \$declaringClass->getName() : \$declaringClass->name)",
                            $methodName          => "(\$method instanceof BetterReflectionMethod ? \$method->getName() : \$method->name)",
                            $declaringMethodName => "(\$declaringMethod instanceof BetterReflectionMethod ? \$declaringMethod->getName() : \$declaringMethod->name)",
                            $fromConstants       => "(\$from instanceof BetterReflectionClass ? \$from->getConstants() : \$from->getReflectionConstants())",
                            $constName           => "(\$const instanceof BetterReflectionClassConstant ? \$const->getName() : \$const->name)",
                            $declaringConstName  => "(\$const->getDeclaringClass() instanceof BetterReflectionClass ? \$const->getDeclaringClass()->getName() : \$const->getDeclaringClass()->name)",
                        },
                    );
                }
            }
        }
    }

    /**
     * Find the first parent node of a given class.
     *
     * @param Node            $node
     * @param class-string<T> $class
     *
     * @return T&Node|null
     *
     * @template     T of Node
     *
     * @noinspection PhpSameParameterValueInspection
     */
    private function findParent(Node $node, string $class): ?Node
    {
        while ($node = $node->getParent()) {
            if ($node instanceof $class) {
                return $node;
            }
        }

        return null;
    }
}
