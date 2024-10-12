<?php

declare(strict_types=1);

namespace Tests\Fixtures\SampleFixers\Strategies;

use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use Tests\Fixtures\SampleFixers\FixerStrategy;

class AppendMiddleFixerStrategy implements FixerStrategy
{
    public function __construct(private string $text, private int $insertingIndex)
    {
    }

    public function execute(Tokens $tokens): void
    {
        $tokens->insertAt(
            $this->insertingIndex,
            new Token($this->text),
        );
    }
}
