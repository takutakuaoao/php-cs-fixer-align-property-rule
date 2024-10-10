<?php

namespace Tests\Units;

use PhpCsFixerAlignPropertyRule\Analyzer;
use PhpCsFixerAlignPropertyRule\TablePosition;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AnalyzerTest extends BaseTestCase
{
    /**
     * @param array<array<string>> $rows
     */
    #[DataProvider('dataProviderForMarkAsRequiringAlign')]
    public function test_mark_text_as_expected(array $rows, ExpectedMarkedText $expected): void
    {
        $analyzer = Analyzer::init($rows);

        $result = $analyzer->markAsRequiringAlign();

        $expected->assertAlignMarked($result);
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function dataProviderForMarkAsRequiringAlign(): array
    {
        return [
            'only_one' => [
                'rows' => [
                    ['text'],
                ],
                'expected' => ExpectedMarkedText::init([
                    'row'    => 0,
                    'column' => 0,
                ], false),
            ],
            'with_right_and_bottom' => [
                'rows' => [
                    ['this-text-must-be-align', 'text'],
                    ['text'],
                ],
                'expected' => ExpectedMarkedText::init([
                    'row'    => 0,
                    'column' => 0,
                ], true),
            ],
            'with_top_and_right_but_not_bottom' => [
                'rows' => [
                    ['text'],
                    ['this-text-must-be-align', 'text'],
                ],
                'expected' => ExpectedMarkedText::init([
                    'row'    => 1,
                    'column' => 0,
                ], true),
            ],
            'with_right_but_not_top_and_bottom' => [
                'rows' => [
                    ['this-text-not-align', 'text'],
                ],
                'expected' => ExpectedMarkedText::init([
                    'row'    => 0,
                    'column' => 0,
                ], false),
            ],
        ];
    }
}

class ExpectedMarkedText
{
    /**
     * @param array{row: int, column: int} $targetPosition
     */
    public static function init(array $targetPosition, bool $mustAlign): self
    {
        return new self(TargetTextPosition::init($targetPosition), $mustAlign);
    }

    public function __construct(
        public readonly TargetTextPosition $targetPosition,
        public readonly bool $mustAlign,
    ) {
    }

    public function assertAlignMarked(Analyzer $actual): void
    {
        $text = $actual->findText($this->targetPosition->toTablePosition());

        TestCase::assertEquals($text?->mustAlign, $this->mustAlign);
    }
}

class TargetTextPosition
{
    /**
     * @param array{row: int, column: int} $targetPosition
     */
    public static function init(array $targetPosition): self
    {
        return new self($targetPosition['row'], $targetPosition['column']);
    }

    public function __construct(
        public readonly int $row,
        public readonly int $column,
    ) {
    }

    public function toTablePosition(): TablePosition
    {
        return TablePosition::fromArray(['row' => $this->row, 'column' => $this->column]);
    }
}
