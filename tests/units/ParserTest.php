<?php

namespace Tests\Units;

use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixerAlignPropertyRule\Core\TablePosition;
use PhpCsFixerAlignPropertyRule\Core\TextTokenTable;
use PhpCsFixerAlignPropertyRule\Parser;
use PHPUnit\Framework\Attributes\DataProvider;

class ParserTest extends BaseTestCase
{
    /**
     * @param array<array{0: int, 1: int, 2: string}> $expected
     */
    #[DataProvider('dataProviderParsingCodes')]
    public function test_parsing_class_property_as_token_table(string $code, array $expected): void
    {
        $tokens = Tokens::fromCode($code);
        $parser = Parser::init($tokens);

        $tokenTable = $parser->parse();

        $this->assertTokenTable($tokenTable, $expected);
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function dataProviderParsingCodes(): array
    {
        return [
            'private_variable' => [
                'code' => '<?php
            class Test
            {
                private $test;
            }
            ',
                'expected' => [
                    [0, 0, 'private'],
                    [0, 1, '$test'],
                ],
            ],
            'protected_variable' => [
                'code' => '<?php
            class Test
            {
                protected $test;
            }
            ',
                'expected' => [
                    [0, 0, 'protected'],
                    [0, 1, '$test'],
                ],
            ],
            'public_variable' => [
                'code' => '<?php
            class Test
            {
                public $test;
            }
            ',
                'expected' => [
                    [0, 0, 'public'],
                    [0, 1, '$test'],
                ],
            ],
            'not_parsed_with_function' => [
                'code' => '<?php
            class Test
            {
                private $test;

                public function test()
                {
                }
            }
                            ',
                'expected' => [
                    [0, 0, 'private'],
                    [0, 1, '$test'],
                ],
            ],
            'not_parsed_with_local_variable' => [
                'code' => '<?php
            class Test
            {
                private $test;

                public function test()
                {
                    $notParsed = "test";
                }
            }
            ',
                'expected' => [
                    [0, 0, 'private'],
                    [0, 1, '$test'],
                ],
            ],
            'readonly_with_variable' => [
                'code' => '<?php
            class Test
            {
                readonly $test;
            }
            ',
                'expected' => [
                    [0, 0, 'readonly'],
                    [0, 1, '$test'],
                ],
            ],
            'static_keyword_with_variable' => [
                'code' => '<?php
            class Test
            {
                static $test;
            }
            ',
                'expected' => [
                    [0, 0, 'static'],
                    [0, 1, '$test'],
                ],
            ],
            'const_keyword_with_value' => [
                'code' => '<?php
            class Test
            {
                const TEST = "const_value";
            }
            ',
                'expected' => [
                    [0, 0, 'const'],
                    [0, 1, 'TEST'],
                    [0, 2, '='],
                    [0, 3, '"const_value"'],
                ],
            ],
            'const_keyword_with_int_value' => [
                'code' => '<?php
            class Test
            {
                const TEST = 1;
            }
            ',
                'expected' => [
                    [0, 0, 'const'],
                    [0, 1, 'TEST'],
                    [0, 2, '='],
                    [0, 3, '1'],
                ],
            ],
            'const_keyword_with_bool_value' => [
                'code' => '<?php
            class Test
            {
                const TEST = true;
            }
            ',
                'expected' => [
                    [0, 0, 'const'],
                    [0, 1, 'TEST'],
                    [0, 2, '='],
                    [0, 3, 'true'],
                ],
            ],
            'multiple_property_keyword_with_value' => [
                'code' => '<?php
            class Test
            {
                private readonly $test;
            }
            ',
                'expected' => [
                    [0, 0, 'private'],
                    [0, 1, 'readonly'],
                    [0, 2, '$test'],
                ],
            ],
            'type_keyword_in_property_sentence' => [
                'code' => '<?php
            class Test
            {
                private SampleClass $test;
            }
            ',
                'expected' => [
                    [0, 0, 'private'],
                    [0, 1, 'SampleClass'],
                    [0, 2, '$test'],
                ],
            ],
            'set_default_value' => [
                'code' => '<?php
            class Test
            {
                private string $test = "default-value";
            }
            ',
                'expected' => [
                    [0, 0, 'private'],
                    [0, 1, 'string'],
                    [0, 2, '$test'],
                    [0, 3, '='],
                    [0, 4, '"default-value"'],
                ],
            ],
            'multiple_property_sentences' => [
                'code' => '<?php
            class Test
            {
                private string $test;
                readonly public int $test_2 = 10;
            }
                ',
                'expected' => [
                    [0, 0, 'private'],
                    [0, 1, 'string'],
                    [0, 2, '$test'],
                    [1, 0, 'readonly'],
                    [1, 1, 'public'],
                    [1, 2, 'int'],
                    [1, 3, '$test_2'],
                    [1, 4, '='],
                    [1, 5, '10'],
                ],
            ],
            'not_parse_static_with_function' => [
                'code' => '<?php
            class Test
            {
                public static function test()
                {

                }

                static public function test2()
                {

                }
            }
                ',
                'expected' => [],
            ],
        ];
    }

    /**
     * @param array<array{0: int, 1: int, 2: string}> $expected
     */
    private function assertTokenTable(TextTokenTable $actual, array $expected): void
    {
        $expectedCount = count($expected);
        $this->assertTrue($actual->getAllItemCount() === $expectedCount, "actual: {$actual->getAllItemCount()}, expected: {$expectedCount}");

        foreach ($expected as $expectRow) {
            $rowIndex    = $expectRow[0];
            $columnIndex = $expectRow[1];
            $expectText  = $expectRow[2];

            $row = $actual->findToken(TablePosition::fromArray(['row' => $rowIndex, 'column' => $columnIndex]));

            $this->assertTrue($row?->hasText($expectText));
        }
    }
}
