<?php

namespace Tests\Units;

use PhpCsFixerAlignPropertyRule\Text;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    #[DataProvider('dataProviderFillingInSpaces')]
    public function test_filling_in_spaces_until_max_number_letters(int $length, string $expected): void
    {
        $text = Text::init('string');

        $result = $text->fillIn($length, 'X');

        $this->assertTrue($result->equal(Text::init($expected)));
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function dataProviderFillingInSpaces(): array
    {
        return [
            'filling_in_space' => [10, 'stringXXXX'],
            'equal_max_length' => [6, 'string'],
            'over_max_length'  => [4, 'string'],
        ];
    }

    #[DataProvider('dataProviderEqual')]
    public function test_equal(Text $text, Text $other, bool $expected): void
    {
        $this->assertEquals($text->equal($other), $expected);
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function dataProviderEqual(): array
    {
        return [
            'same'     => [Text::init('1'), Text::init('1'), true],
            'not_same' => [Text::init('1'), Text::init('2'), false],
        ];
    }
}
