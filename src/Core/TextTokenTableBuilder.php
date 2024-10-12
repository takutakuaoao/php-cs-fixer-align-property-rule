<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule\Core;

class TextTokenTableBuilder
{
    /** @var array<array<TextToken>> */
    private array $rows;

    private TablePosition $tablePosition;

    public static function init(): self
    {
        return new self();
    }

    public function __construct()
    {
        $this->rows          = [];
        $this->tablePosition = TablePosition::init();
    }

    public function addColumn(int $index, string $text): self
    {
        $this->rows          = $this->tablePosition->apply($this->rows, TextToken::init($index, $text, $this->tablePosition));
        $this->tablePosition = $this->tablePosition->right();

        return $this;
    }

    public function addNewRow(int $index, string $text): self
    {
        $this->tablePosition = $this->tablePosition->bottomHead();
        $this->rows          = $this->tablePosition->apply($this->rows, TextToken::init($index, $text, $this->tablePosition));

        return $this;
    }

    public function build(): TextTokenTable
    {
        return TextTokenTable::init($this->rows);
    }
}
