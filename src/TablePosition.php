<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule;

class TablePosition
{
    public static function init(): self
    {
        return new self(0, 0);
    }

    /**
     * @param array{row: int, column: int} $position
     */
    public static function fromArray(array $position): self
    {
        return new self($position['row'], $position['column']);
    }

    public function __construct(private int $row, private int $column)
    {
    }

    public function update(int $row, int $column): self
    {
        return new TablePosition($row, $column);
    }

    public function right(): self
    {
        return $this->update($this->row, $this->column + 1);
    }

    public function top(): self
    {
        return $this->update($this->row - 1, $this->column);
    }

    public function bottom(): self
    {
        return $this->update($this->row + 1, $this->column);
    }

    /**
     * @return array{row: int, column: int}
     */
    public function toArray(): array
    {
        return [
            'row'    => $this->row,
            'column' => $this->column,
        ];
    }
}
