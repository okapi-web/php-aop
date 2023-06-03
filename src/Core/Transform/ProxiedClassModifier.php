<?php
/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Core\Transform;

use DI\Attribute\Inject;
use Microsoft\PhpParser\Node;
use Microsoft\PhpParser\Node\SourceFileNode;
use Microsoft\PhpParser\Node\Statement\ClassDeclaration;
use Microsoft\PhpParser\Token;
use Microsoft\PhpParser\TokenKind;
use Okapi\Aop\Core\Cache\CachePaths;
use Okapi\CodeTransformer\Core\DI;
use Okapi\CodeTransformer\Core\StreamFilter\Metadata;
use Okapi\CodeTransformer\Transformer\Code;

/**
 * # Proxied Class Modifier
 *
 * This class is responsible for modifying the proxied class.
 */
class ProxiedClassModifier
{
    // region DI

    #[Inject]
    private CachePaths $cachePaths;

    // endregion

    /**
     * The code class.
     *
     * @var Code
     */
    private Code $code;

    /**
     * The source file node.
     *
     * @var SourceFileNode
     */
    private SourceFileNode $sourceFileNode;

    /**
     * The proxied class name.
     *
     * @var string
     */
    private string $proxiedClassName;

    /**
     * Callbacks to process tokens.
     *
     * @var callable<Token>[]
     */
    private array $tokenCallbacks = [];

    /**
     * Callbacks to process nodes.
     *
     * @var callable<Node>[]
     */
    private array $nodeCallbacks = [];

    /**
     * ProxiedClassModifier constructor.
     *
     * @param Metadata $metadata
     */
    public function __construct(
        private readonly Metadata $metadata,
    ) {
        $cachePaths = DI::get(CachePaths::class);

        $this->code             = $this->metadata->code;
        $this->sourceFileNode   = $this->code->getSourceFileNode();
        $this->proxiedClassName = $this->code->getClassName() . $cachePaths::PROXIED_SUFFIX;
    }

    /**
     * Modify the proxied class.
     *
     * @return void
     */
    public function modify(): void
    {
        $this->convertToProxy();
        $this->unFinalMethods();
        $this->changeVisibility();
        $this->replaceSelfType();

        $sourceFileNode = $this->metadata->code->getSourceFileNode();

        // Iterate over the nodes
        foreach ($sourceFileNode->getDescendantNodes() as $node) {
            foreach ($this->nodeCallbacks as $callback) {
                $callback($node);
            }
        }

        // Iterate over the tokens
        foreach ($sourceFileNode->getDescendantTokens() as $token) {
            foreach ($this->tokenCallbacks as $callback) {
                $callback($token);
            }
        }
    }

    /**
     * Convert the proxied class to a class that extends the proxied class.
     *
     * @return void
     */
    private function convertToProxy(): void
    {
        // Find the class declaration
        $node = $this->sourceFileNode->getFirstDescendantNode(ClassDeclaration::class);
        assert($node instanceof ClassDeclaration);

        // Replace the class name
        $this->code->edit(
            $node->name,
            $this->proxiedClassName,
        );

        // Append the child class
        $childClassPath = $this->cachePaths->getWovenCachePath($this->metadata->uri);
        // language=PHP
        $codeToAppend = "\ninclude_once '$childClassPath';";
        $this->code->append($codeToAppend);
    }

    /**
     * Remove the final keyword from the methods.
     *
     * @return void
     */
    private function unFinalMethods(): void
    {
        $this->tokenCallbacks[] = function (Token $token) {
            if ($token->kind === TokenKind::FinalKeyword) {
                $this->code->edit($token, '');
            }
        };
    }

    /**
     * Change the visibility of the class members.
     *
     * @return void
     */
    private function changeVisibility(): void
    {
        $this->tokenCallbacks[] = function (Token $token) {
            if ($token->kind === TokenKind::PrivateKeyword
                || $token->kind === TokenKind::ProtectedKeyword
            ) {
                $this->code->edit(
                    $token,
                    'public',
                );
            }
        };
    }

    /**
     * Replace self type with the original class name.
     *
     * @return void
     */
    private function replaceSelfType(): void
    {
        $this->nodeCallbacks[] = function (Node $node) {
            // Replace parameter object types with the proxied class name
            if ($node instanceof Node\Parameter
                && $node->typeDeclarationList instanceof Node\DelimitedList\QualifiedNameList
            ) {
                foreach ($node->typeDeclarationList->children as $type) {
                    if ($type instanceof Node\QualifiedName) {
                        $this->replaceParameterSelfType($type);
                    }
                }
            }

            // Replace return object types with the proxied class name
            if ($node instanceof Node\MethodDeclaration
                && $node->returnTypeList instanceof Node\DelimitedList\QualifiedNameList
            ) {
                foreach ($node->returnTypeList->children as $returnType) {
                    if ($returnType instanceof Node\QualifiedName) {
                        $this->replaceReturnSelfType($returnType);
                    }
                }
            }

            // Replace self object creations with the proxied class name
            if ($node instanceof Node\Expression\ObjectCreationExpression) {
                if ($node->classTypeDesignator->getText() === 'self') {
                    $this->replaceObjectCreationSelfType($node);
                }
            }
        };
    }

    /**
     * Replace the parameter self type with the proxied class name.
     *
     * @param Node\QualifiedName $qualifiedName
     *
     * @return void
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    private function replaceParameterSelfType(
        Node\QualifiedName $qualifiedName,
    ): void {
        $typeText = $qualifiedName->getText();

        // Self
        if ($typeText === 'self') {
            $this->code->edit(
                $qualifiedName,
                $this->proxiedClassName,
            );
        } else {
            // Other classes that have a proxy
            /** @noinspection PhpUnhandledExceptionInspection */
            $fullClassName  = '\\' . $qualifiedName->getResolvedName()
                ->getFullyQualifiedNameText();
            $proxyClassName = $fullClassName
                . $this->cachePaths::PROXIED_SUFFIX;

            // Autoload the class with class_exists,
            // so we can check if the proxy exists
            if (class_exists($fullClassName)
                && class_exists($proxyClassName)
            ) {
                $this->code->edit(
                    $qualifiedName,
                    $proxyClassName,
                );
            }
        }
    }

    /**
     * Replace the return self type with the proxied class name.
     *
     * @param Node\QualifiedName $qualifiedName
     *
     * @return void
     */
    private function replaceReturnSelfType(
        Node\QualifiedName $qualifiedName,
    ): void {
        // Self
        if ($qualifiedName->getText() === 'self') {
            $this->code->edit(
                $qualifiedName,
                $this->proxiedClassName,
            );
        }
    }

    /**
     * Replace the object creation self type with the proxied class name.
     *
     * @param Node\Expression\ObjectCreationExpression $objectCreationExpression
     *
     * @return void
     */
    private function replaceObjectCreationSelfType(
        Node\Expression\ObjectCreationExpression $objectCreationExpression,
    ): void {
        $this->code->edit(
            $objectCreationExpression->classTypeDesignator,
            '\\' . $this->code->getNamespacedClass(),
        );
    }
}
