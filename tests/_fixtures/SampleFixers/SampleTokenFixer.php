<?php

declare(strict_types=1);

namespace Tests\Fixtures\SampleFixers;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Tokens;

class SampleTokenFixer extends AbstractFixer
{
    public static function init(FixerStrategy $fixerStrategy): self
    {
        return new self($fixerStrategy);
    }

    public function __construct(private FixerStrategy $fixerStrategy)
    {
    }

    protected function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        $this->fixerStrategy->execute($tokens);
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return true;
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'For learning test',
            [new CodeSample('')],
            null,
            '',
        );
    }
}
