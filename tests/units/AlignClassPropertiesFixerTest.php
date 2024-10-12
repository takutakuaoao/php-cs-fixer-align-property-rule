<?php

namespace Tests\Units;

use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixerAlignPropertyRule\AlignClassPropertiesFixer;
use PHPUnit\Framework\Attributes\DataProvider;

class AlignClassPropertiesFixerTest extends BaseTestCase
{
    private AlignClassPropertiesFixer $fixer;

    public function setUp(): void
    {
        $this->fixer = AlignClassPropertiesFixer::init();
    }

    public function test_fix(): void
    {
        $tokens = Tokens::fromCode('<?php
        class Test
        {
            private int $int;
            private string $string;
        }
        ');

        $this->fixer->fix(new \SplFileInfo(__FILE__), $tokens);

        $this->assertEquals('<?php
        class Test
        {
            private int    $int;
            private string $string;
        }
        ', $tokens->generateCode());
    }

    #[DataProvider('dataProviderForHasClassStatement')]
    public function test_has_class_statement(string $code, bool $expected): void
    {
        $tokens = Tokens::fromCode($code);

        $result = $this->fixer->hasClassStatement($tokens);

        $this->assertEquals($result, $expected);
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function dataProviderForHasClassStatement(): array
    {
        return [
            'class' => [
                'code' => '
                <?php

                class Test
                {
                }
                ',
                'expected' => true,
            ],
            'abstract' => [
                'code' => '
                <?php

                abstract class Test
                {
                }
                ',
                'expected' => true,
            ],
            'interface' => [
                'code' => '
                <?php

                interface Test
                {
                }
                ',
                'expected' => false,
            ],
        ];
    }

    #[DataProvider('dataProviderForHasClassPropertyStatement')]
    public function test_has_class_property_statement(string $code, bool $expected): void
    {
        $tokens = Tokens::fromCode($code);

        $result = $this->fixer->hasClassProperty($tokens);

        $this->assertEquals($result, $expected);
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function dataProviderForHasClassPropertyStatement(): array
    {
        return [
            'private with variable' => [
                'code' => '
                <?php

                class Test
                {
                    private $text;
                }
                ',
                'expected' => true,
            ],
            'protected with variable' => [
                'code' => '
                <?php

                class Test
                {
                    protected $text;
                }
                ',
                'expected' => true,
            ],
            'public with variable' => [
                'code' => '
                <?php

                class Test
                {
                    public $text;
                }
                ',
                'expected' => true,
            ],
        ];
    }
}
