<?php

namespace Tests\Units;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixerAlignPropertyRule\AlignClassPropertiesFixer;

class AlignClassPropertiesFixerTest extends BaseTestCase
{
    private AlignClassPropertiesFixer $fixer;
    private \SplFileInfo $file;

    public function setUp(): void
    {
        $this->fixer = AlignClassPropertiesFixer::init();
        $this->file  = new \SplFileInfo(__FILE__);
    }

    public function test_not_formatting_code_outside_class_statement(): void
    {
        $code = '
        <?php
        $local = "test";
        $local_2 = "test";
        ';

        $tokens = Tokens::fromCode($code);

        $this->fixer->fix($this->file, $tokens);

        $this->assertTrue($this->fixer instanceof FixerInterface);
        $this->assertEquals($tokens->generateCode(), $code);
    }

    public function test_format(): void
    {
        $code = '
        <?php

        class Test
        {
            public $test;
            protected $test2;
        }
        ';

        $expected = '
        <?php

        class Test
        {
            public    $test;
            protected $test2;
        }
        ';

        $tokens = Tokens::fromCode($code);

        $this->fixer->fix($this->file, $tokens);

        $this->assertEquals($expected, $tokens->generateCode());
    }
}
