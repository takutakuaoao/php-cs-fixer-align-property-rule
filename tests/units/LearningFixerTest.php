<?php

namespace Tests\Units;

use PhpCsFixer\Fixer\Alias\ArrayPushFixer as SampleFixer;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;

class LearningFixerTest extends TestCase
{
    private SampleFixer $fixer;
    private \SplFileInfo $mockFile;

    public function setUp(): void
    {
        Tokens::clearCache();

        $this->fixer    = new SampleFixer();
        $this->mockFile = new \SplFileInfo(__FILE__);
    }

    public function test_learning_how_to_fix_code(): void
    {
        $tokens = Tokens::fromCode('<?php array_push($a,$b);');

        $this->fixer->fix($this->mockFile, $tokens);

        $this->assertEquals($tokens->generateCode(), '<?php $a[] =$b;');
    }
}
