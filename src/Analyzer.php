<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule;

class Analyzer
{
    private TablePosition $currentPosition;

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
        $this->currentPosition = TablePosition::init();
    }

    public function markAsRequiringAlign(): self
    {
        foreach ($this->rows as $rowIndex => $row) {
            foreach ($row as $columnIndex => $currentToken) {
                $this->updateCurrentPosition((int) $rowIndex, (int) $columnIndex);

                if ($this->isMarkAsRequiredAlign()) {
                    $this->updateCurrentToken($currentToken->markAsRequiredAlign());
                }
            }
        }

        return $this;
    }

    private function updateCurrentPosition(int $row, int $column): void
    {
        $this->currentPosition = $this->currentPosition->update($row, $column);
    }

    private function updateCurrentToken(TextToken $updatedTextToken): void
    {
        $current                                         = $this->currentPosition->toArray();
        $this->rows[$current['row']][$current['column']] = $updatedTextToken;
    }

    private function isMarkAsRequiredAlign(): bool
    {
        return $this->findText($this->currentPosition->right())
            && (
                $this->findText($this->currentPosition->bottom())
                || $this->findText($this->currentPosition->top())
            );
    }

    public function findText(TablePosition $position): ?TextToken
    {
        $arrPosition = $position->toArray();

        if (isset($this->rows[$arrPosition['row']][$arrPosition['column']])) {
            return $this->rows[$arrPosition['row']][$arrPosition['column']];
        }

        return null;
    }
}
