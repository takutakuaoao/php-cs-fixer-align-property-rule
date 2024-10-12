<?php

declare(strict_types=1);

namespace Tests\Fixtures\SampleFixers\Strategies;

use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use Tests\Fixtures\SampleFixers\FixerStrategy;

class RemovingContinuousSpaceFixerStrategy implements FixerStrategy
{
    public function execute(Tokens $tokens): void
    {
        foreach ($tokens as $index => $token) {
            if ($token?->isWhitespace()) {
                $prevTagName = $tokens->offsetGet($index - 1)->getName();
                if (in_array($prevTagName, ['T_OPEN_TAG', 'T_CONSTANT_ENCAPSED_STRING'])) {
                    $tokens->offsetSet($index, new Token(''));
                } else {
                    $tokens->offsetSet($index, new Token(' '));
                }
            }
        }
    }
}
