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
        $result = array_map(function ($row, $rowIndex) {
            return array_map(function ($text, $columnIndex) use ($rowIndex) {
                return TextToken::init($text, ['row' => $rowIndex, 'column' => $columnIndex]);
            }, $row, range(0, count($row) - 1));
        }, $rows, range(0, count($rows) - 1));

        return new self($result);
    }

    /**
     * @param array<array<TextToken>> $rows
     */
    public function __construct(private array $rows)
    {
    }

    public function updateToken(TextToken $updated): self
    {
        $rows = $this->rows;
        $pos  = $updated->originalPosition->toArray();

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

    /**
     * @return array<array<TextToken>>
     */
    public function chunkVertical(int $column): array
    {
        $maxRowLength = count($this->rows);

        $result = [];
        $chunk  = [];

        for ($row = 0; $row < $maxRowLength; ++$row) {
            $token = $this->findToken(TablePosition::fromArray(['row' => $row, 'column' => $column]));

            if ($token) {
                $chunk[] = $token;
            } else {
                [$result, $chunk] = self::pourChunkToResult($result, $chunk);
            }
        }

        [$result] = self::pourChunkToResult($result, $chunk);

        return $result;
    }

    /**
     * @return array<array<TextToken>>
     */
    public function chunkVerticalAll(): array
    {
        $maxColumn = [];

        foreach ($this->rows as $row) {
            $maxColumn[] = count($row);
        }

        $maxColumn = (int) max($maxColumn);

        $result = [];
        for ($i = 0; $i < $maxColumn; ++$i) {
            $result = array_merge($result, $this->chunkVertical($i));
        }

        return $result;
    }

    /**
     * @param array<array<TextToken>> $result
     * @param array<TextToken>        $chunk
     *
     * @return array{0: array<array<TextToken>>, 1: array<TextToken>}
     */
    private static function pourChunkToResult(array $result, array $chunk): array
    {
        if ([] !== $chunk) {
            $result[] = $chunk;
            $chunk    = [];

            return [$result, $chunk];
        }

        return [$result, $chunk];
    }

    /**
     * @return array<array<string>>
     */
    public function toPlain(): array
    {
        return array_map(function (array $row) {
            return array_map(function (TextToken $token) {
                return $token->toString();
            }, $row);
        }, $this->rows);
    }
}
