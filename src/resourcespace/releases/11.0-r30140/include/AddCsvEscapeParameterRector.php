<?php

declare(strict_types=1);

namespace Montala\ResourceSpace\Utils\Rector;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddCsvEscapeParameterRector extends AbstractRector
{
    private const CSV_FUNCTIONS = [
        'fputcsv',
        'fgetcsv',
        'str_getcsv',
    ];

    private const CSV_METHODS = [
        'fputcsv',
        'fgetcsv',
        'setCsvControl',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add explicit CSV escape parameter for PHP 8.4+',
            [
                new CodeSample(
                    <<<'CODE'
fputcsv($handle, $row);
$data = str_getcsv($line);
$file->setCsvControl(',');
CODE,
                    <<<'CODE'
fputcsv($handle, $row, escape: '\\');
$data = str_getcsv($line, escape: '\\');
$file->setCsvControl(',', escape: '\\');
CODE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [
            FuncCall::class,
            MethodCall::class,
        ];
    }

    public function refactor(Node $node): ?Node
    {
        if ($node instanceof FuncCall) {
            if (!$this->isTargetFunction($node)) {
                return null;
            }

            if ($this->hasEscapeArgument($node->args)) {
                return null;
            }

            $node->args[] = $this->createEscapeArg();

            return $node;
        }

        if ($node instanceof MethodCall) {
            if (!$this->isTargetMethod($node)) {
                return null;
            }

            if ($this->hasEscapeArgument($node->args)) {
                return null;
            }

            $node->args[] = $this->createEscapeArg();

            return $node;
        }

        return null;
    }

    private function isTargetFunction(FuncCall $funcCall): bool
    {
        if (!$funcCall->name instanceof Name) {
            return false;
        }

        return in_array($this->getName($funcCall), self::CSV_FUNCTIONS, true);
    }

    private function isTargetMethod(MethodCall $methodCall): bool
    {
        return in_array($this->getName($methodCall->name), self::CSV_METHODS, true);
    }

    /**
     * @param Arg[] $args
     */
    private function hasEscapeArgument(array $args): bool
    {
        foreach ($args as $arg) {
            if ($arg->name instanceof Identifier && $arg->name->toString() === 'escape') {
                return true;
            }
        }

        return false;
    }

    private function createEscapeArg(): Arg
    {
        return new Arg(
            value: new String_('\\'),
            name: new Identifier('escape'),
        );
    }
}
