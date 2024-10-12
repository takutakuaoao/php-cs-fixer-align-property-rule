<?php

declare(strict_types=1);

namespace Tests\Fixtures\SampleFixers\Strategies;

use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use Tests\Fixtures\SampleFixers\FixerStrategy;

class LineBreakFixerStrategy implements FixerStrategy
{
    public function execute(Tokens $tokens): void
    {
        foreach ($tokens as $index => $token) {
            if ($token?->isWhitespace() && false !== strpos($token->getContent(), "\n")) {
                $tokens->offsetSet($index, new Token(' //' . $token->getContent()));
            }
        }
    }
}
