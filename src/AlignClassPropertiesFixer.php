<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Tokens;

class AlignClassPropertiesFixer extends AbstractFixer
{
    public static function init(): self
    {
        return new self();
    }

    public function __construct()
    {
    }

    protected function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return false;
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            '',
            [new CodeSample('')],
            null,
            '',
        );
    }
}
