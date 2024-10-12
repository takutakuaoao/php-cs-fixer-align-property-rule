<?php

declare(strict_types=1);

namespace Tests\Fixtures\SampleFixers;

use PhpCsFixer\Tokenizer\Tokens;

interface FixerStrategy
{
    public function execute(Tokens $tokens): void;
}
