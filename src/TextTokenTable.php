<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule;

class TextTokenTable
{
    /**
     * @param array<array<string>> $rows
     */
    public static function init(array $rows): self
    {
        $result = array_map(function ($row) {
            return array_map(function ($text) {
                return TextToken::init($text);
            }, $row);
        }, $rows);

        return new self($result);
    }

    /**
     * @param array<array<TextToken>> $rows
     */
    public function __construct(private array $rows)
    {
    }

    public function updateToken(TablePosition $tablePosition, TextToken $updated): self
    {
        $rows = $this->rows;
        $pos  = $tablePosition->toArray();

        $rows[$pos['row']][$pos['column']] = $updated;

        return new self($rows);
    }

    public function findToken(TablePosition $tablePosition): ?TextToken
    {
        $pos = $tablePosition->toArray();

        if (isset($this->rows[$pos['row']][$pos['column']])) {
            return $this->rows[$pos['row']][$pos['column']];
        }

        return null;
    }

    /**
     * @param callable(TablePosition, TextToken): void $callback
     */
    public function loop(callable $callback): void
    {
        foreach ($this->rows as $rowIndex => $row) {
            foreach ($row as $columnIndex => $currentToken) {
                $callback(
                    TablePosition::fromArray(['row' => $rowIndex, 'column' => $columnIndex]),
                    $currentToken
                );
            }
        }
    }
}
