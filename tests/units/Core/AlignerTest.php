<?php

namespace Tests\Units;

use PhpCsFixerAlignPropertyRule\Core\Aligner;
use PhpCsFixerAlignPropertyRule\Core\TextToken;
use PHPUnit\Framework\TestCase;

class AlignerTest extends TestCase
{
    public function test_align_only_required(): void
    {
        $aligner = Aligner::initFromToken(
            TextToken::init(0, 'text', ['row' => 0, 'column' => 0])->markAsRequiredAlign(),
            TextToken::init(1, 'longText', ['row' => 1, 'column' => 0]),
            TextToken::init(2, 't', ['row' => 2, 'column' => 0])->markAsRequiredAlign()
        );

        $result = $aligner->align();

        $this->assertTrue($result->hasTexts([
            'text    ',
            'longText',
            't       ',
        ]));
    }
}
