<?php

declare(strict_types=1);

namespace Tests\Fixtures\SampleFixers\Strategies;

use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use Tests\Fixtures\SampleFixers\FixerStrategy;

class AppendLastFixerStrategy implements FixerStrategy
{
    public function __construct(private string $appendText)
    {
    }

    public function execute(Tokens $tokens): void
    {
        $count = count($tokens);

        $tokens->insertAt($count, [
            new Token($this->appendText),
        ]);
    }
}
