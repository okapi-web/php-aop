<?php

namespace Okapi\Aop\Tests\Integration\TransformerAndAspectDependencyInjectionHandler;

use Microsoft\PhpParser\Node\DelimitedList\QualifiedNameList;
use Microsoft\PhpParser\Node\MethodDeclaration;
use Microsoft\PhpParser\Node\NumericLiteral;
use Okapi\CodeTransformer\Transformer as TransformerClass;
use Okapi\CodeTransformer\Transformer\Code;

class Transformer extends TransformerClass
{
    public function getTargetClass(): string|array
    {
        return Target::class;
    }

    /** @noinspection PhpPossiblePolymorphicInvocationInspection */
    public function transform(Code $code): void
    {
        $sourceFileNode = $code->getSourceFileNode();

        foreach ($sourceFileNode->getDescendantNodes() as $node) {
            if ($node instanceof QualifiedNameList
                && $node->getFirstAncestor(MethodDeclaration::class)?->getName() === 'answer'
            ) {
                $code->edit($node, 'int|float');
            }

            if ($node instanceof NumericLiteral
                && $node->getFirstAncestor(MethodDeclaration::class)?->getName() === 'answer'
            ) {
                $text = $node->getText();
                $code->edit($node, "$text.69");
            }
        }
    }
}
