<?php
/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Core\Transform;

use DI\Attribute\Inject;
use Microsoft\PhpParser\Node;
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
     * @var Node\SourceFileNode
     */
    private Node\SourceFileNode $sourceFileNode;

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
     * @var (Node|Token)[]
     */
    private array $alreadyProcessed = [];

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

    /** @noinspection PhpUnused Is used at runtime for proxied classes */
    public static function resolveStaticClass(string $staticClass): string
    {
        return str_replace(
            CachePaths::PROXIED_SUFFIX,
            '',
            $staticClass,
        );
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
        $this->replaceMagicConstants();

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

    private function edit(
        Node|Token $nodeOrToken,
        string $replacement,
    ): void {
        if (in_array($nodeOrToken, $this->alreadyProcessed, true)) {
            return;
        }

        $this->code->edit(
            $nodeOrToken,
            $replacement,
        );

        $this->alreadyProcessed[] = $nodeOrToken;
    }

    /**
     * Convert the proxied class to a class that extends the proxied class.
     *
     * @return void
     */
    private function convertToProxy(): void
    {
        // Find the class declaration
        $node = $this->sourceFileNode->getFirstDescendantNode(Node\Statement\ClassDeclaration::class);
        assert($node instanceof Node\Statement\ClassDeclaration);

        // Replace the class name
        $this->edit(
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
                $this->edit($token, '');
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
                $this->edit(
                    $token,
                    'public',
                );
            }
        };
    }

    // region Self Types

    /**
     * Replace self type with the original class name.
     *
     * @return void
     */
    private function replaceSelfType(): void
    {
        $this->nodeCallbacks[] = function (Node $node) {
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
            $this->edit(
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
        $this->edit(
            $objectCreationExpression->classTypeDesignator,
            '\\' . $this->code->getNamespacedClass(),
        );
    }

    // endregion

    /**
     * Replace magic constants like {@see __DIR__} to their actual path.
     *
     * @return void
     *
     * @see https://www.php.net/manual/language.constants.magic.php
     */
    private function replaceMagicConstants(): void
    {
        $this->nodeCallbacks[] = function (Node $node) {
            if ($node instanceof Node\QualifiedName) {
                $text = $node->getText();

                switch ($text) {
                    case '__DIR__':
                        $originalParentDir = dirname($this->getOriginalFileDir());

                        $this->edit($node, "'$originalParentDir'");
                        break;

                    case '__FILE__':
                        $originalFileDir = $this->getOriginalFileDir();

                        $this->edit($node, "'$originalFileDir'");
                        break;

                    case '__CLASS__':
                        $originalNamespacedClassName = $this->code->getNamespacedClass();

                        $this->edit($node, "'$originalNamespacedClassName'");
                        break;

                    case '__METHOD__':
                        $methodNode = $node->getFirstAncestor(Node\MethodDeclaration::class);
                        if (!$methodNode) {
                            break;
                        }
                        assert($methodNode instanceof Node\MethodDeclaration);

                        $originalNamespacedClassName = $this->code->getNamespacedClass();
                        $originalMethodName = $methodNode->getName();

                        $this->edit(
                            $node,
                            "'$originalNamespacedClassName::$originalMethodName'",
                        );
                        break;

                    case 'self':
                        $originalClassName = $this->code->getClassName();

                        $this->edit(
                            $node,
                            $originalClassName,
                        );
                        break;
                }
            }

            if ($node instanceof Node\Expression\ScopedPropertyAccessExpression) {
                if ($node->getText() === 'static::class') {
                    $this->edit(
                        $node,
                        '\\' . self::class . '::resolveStaticClass(static::class)',
                    );
                }
            }
        };
    }

    private function getOriginalFileDir(): string
    {
        return $this->metadata->uri;
    }
}
