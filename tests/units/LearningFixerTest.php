<?php

namespace Tests\Units;

use PhpCsFixer\Fixer\Alias\ArrayPushFixer;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\SampleFixers\SampleTokenFixer;
use Tests\Fixtures\SampleFixers\Strategies\AppendLastFixerStrategy;
use Tests\Fixtures\SampleFixers\Strategies\AppendMiddleFixerStrategy;
use Tests\Fixtures\SampleFixers\Strategies\LineBreakFixerStrategy;
use Tests\Fixtures\SampleFixers\Strategies\RemovingContinuousSpaceFixerStrategy;

class LearningFixerTest extends TestCase
{
    private ArrayPushFixer $fixer;
    private \SplFileInfo $mockFile;

    public function setUp(): void
    {
        Tokens::clearCache();

        $this->fixer    = new ArrayPushFixer();
        $this->mockFile = new \SplFileInfo(__FILE__);
    }

    public function test_learning_how_to_fix_code(): void
    {
        $tokens = Tokens::fromCode('<?php array_push($a,$b);');

        $this->fixer->fix($this->mockFile, $tokens);

        $this->assertEquals($tokens->generateCode(), '<?php $a[] =$b;');
    }

    public function test_learning_insert_token(): void
    {
        $tokens = Tokens::fromCode('<?php ');

        SampleTokenFixer::init(new AppendLastFixerStrategy('$a = "test";'))->fix($this->mockFile, $tokens);

        self::assertEquals($tokens->generateCode(), '<?php $a = "test";');
    }

    public function test_learning_in_the_middle_insert_token(): void
    {
        $tokens = Tokens::fromCode('<?php $a = "test";');

        SampleTokenFixer::init(new AppendMiddleFixerStrategy('$b = "middle"; ', 1))->fix($this->mockFile, $tokens);

        self::assertEquals($tokens->generateCode(), '<?php $b = "middle"; $a = "test";');
    }

    public function test_removing_continuous_spaces_until_they_are_last_one(): void
    {
        $tokens = Tokens::fromCode('<?php      $a    = "test"     ;');

        SampleTokenFixer::init(new RemovingContinuousSpaceFixerStrategy())->fix($this->mockFile, $tokens);

        self::assertEquals('<?php $a = "test";', $tokens->generateCode());
    }

    public function test_inserting_some_comments_before_line_break(): void
    {
        $tokens = Tokens::fromCode('
        <?php
        $a = "test";
        $b = "test2";
        ');

        SampleTokenFixer::init(new LineBreakFixerStrategy())->fix($this->mockFile, $tokens);

        self::assertEquals('
        <?php
        $a = "test"; //
        $b = "test2"; //
        ', $tokens->generateCode());
    }
}
