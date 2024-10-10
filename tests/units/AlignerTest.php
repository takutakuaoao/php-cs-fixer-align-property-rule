<?php

namespace Tests\Units;

use PhpCsFixerAlignPropertyRule\Aligner;
use PhpCsFixerAlignPropertyRule\TextToken;
use PHPUnit\Framework\TestCase;

class AlignerTest extends TestCase
{
    public function test_align_only_required(): void
    {
        $aligner = Aligner::initFromToken(
            TextToken::init('text', ['row' => 0, 'column' => 0])->markAsRequiredAlign(),
            TextToken::init('longText', ['row' => 1, 'column' => 0]),
            TextToken::init('t', ['row' => 2, 'column' => 0])->markAsRequiredAlign()
        );

        $result = $aligner->align();

        $this->assertTrue($result->hasTexts([
            'text    ',
            'longText',
            't       ',
        ]));
    }
}
