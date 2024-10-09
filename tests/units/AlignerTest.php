<?php

namespace Tests\Units;

use PhpCsFixerAlignPropertyRule\Aligner;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AlignerTest extends TestCase
{
    public function test_padding_each_text_until_max_number_of_letters(): void
    {
        $sut = Aligner::init('1', '11', '111', '1111');

        $result = $sut->align();

        $expected = Aligner::init(
            '1   ',
            '11  ',
            '111 ',
            '1111',
        );
        $this->assertTrue($expected->equal($result));
    }

    #[DataProvider('dataProviderMaxNumberLetters')]
    public function test_get_max_number_of_letters(string $first, string $second, int $expectedMostLetterLength): void
    {
        $aligner = Aligner::init($first, $second);

        $result = $aligner->getMaxLettersNumber();

        $this->assertEquals($expectedMostLetterLength, $result);
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function dataProviderMaxNumberLetters(): array
    {
        return [
            'first_long'  => ['stringLonger', 'string', 12],
            'second_long' => ['string', 'strings', 7],
            'same'        => ['string', 'string', 6],
        ];
    }

    /**
     * @param array<string> $self
     * @param array<string> $other
     */
    #[DataProvider('dataProviderTextListForEqual')]
    public function test_equal(array $self, array $other, bool $expected): void
    {
        $self  = Aligner::init(...$self);
        $other = Aligner::init(...$other);

        $this->assertEquals($self->equal($other), $expected);
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function dataProviderTextListForEqual(): array
    {
        return [
            'same'       => [['string1', 'string2'], ['string1', 'string2'], true],
            'not_same_1' => [['string1', 'string2'], ['string1', 'string2', 'string3'], false],
            'not_same_2' => [['string1', 'string2', 'string999'], ['string1', 'string2', 'string3'], false],
        ];
    }
}
