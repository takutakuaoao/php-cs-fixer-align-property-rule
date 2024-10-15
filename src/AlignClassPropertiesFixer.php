<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixerAlignPropertyRule\Core\Formatter;

class AlignClassPropertiesFixer extends AbstractFixer
{
    public static function init(): self
    {
        return new self();
    }

    public function __construct()
    {
    }

    public function getName(): string
    {
        return 'Takutakuaoao/align_class_properties';
    }

    protected function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        $tokenTables = Parser::init($tokens)->parse();

        foreach ($tokenTables as $tokenTable) {
            $formatted = Formatter::init($tokenTable)->format();

            $formatted->loop(function ($_, $token) use ($tokens) {
                if ($token->isMustAlign()) {
                    $tokens->offsetSet($token->index, new Token($token->toString()));
                    $tokens->offsetSet($token->index + 1, new Token(' '));
                }
            });
        }
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $this->hasClassStatement($tokens);
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Align vertical position of class properties.',
            [new CodeSample('<?php
class Sample
{
    private string $testString;
    public int $testInt;
    protected bool $testBool;

    readonly private string $testString2;
    readonly public int $testInt2;
    readonly protected bool $testBool2;
}
')],
            null,
            '',
        );
    }

    public function hasClassStatement(Tokens $tokens): bool
    {
        foreach ($tokens as $index => $token) {
            if ($token?->equals([T_CLASS, 'class'], true)) {
                return true;
            }
        }

        return false;
    }

    public function hasClassProperty(Tokens $tokens): bool
    {
        $hasProperty = false;

        foreach ($tokens as $token) {
            if ($this->isPropertyToken($token)) {
                $hasProperty = true;
            }
        }

        return $hasProperty;
    }

    private function isPropertyToken(?Token $token): bool
    {
        if (null === $token) {
            return false;
        }

        return $token->equalsAny([
            [T_PRIVATE, 'private'],
            [T_PROTECTED, 'protected'],
            [T_PUBLIC, 'public'],
        ], true);
    }
}
