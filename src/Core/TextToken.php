<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule\Core;

class TextToken
{
    /**
     * @param array{row: int, column: int}|TablePosition $position
     */
    public static function init(int $index, string $text, array|TablePosition $position): self
    {
        $position = is_array($position) ? TablePosition::fromArray($position) : $position;

        return new self($index, Text::init($text), false, $position);
    }

    public function __construct(
        public readonly int $index,
        private Text $text,
        private bool $mustAlign,
        private TablePosition $originalPosition,
    ) {
    }

    public function fillIn(int $maxLettersNumber, string $space = ' '): TextToken
    {
        if (!$this->mustAlign) {
            return $this;
        }

        return new TextToken(
            $this->index,
            $this->text->fillIn($maxLettersNumber, $space),
            $this->mustAlign,
            $this->originalPosition
        );
    }

    public function length(): int
    {
        return $this->text->length();
    }

    public function markAsRequiredAlign(): self
    {
        return new self($this->index, $this->text, true, $this->originalPosition);
    }

    public function hasText(string $text): bool
    {
        return $this->text->equal(Text::init($text));
    }

    /**
     * @param array<array<TextToken>> $table
     *
     * @return array<array<TextToken>>
     */
    public function insertSelfTo(array $table): array
    {
        return $this->originalPosition->apply($table, $this);
    }

    public function toString(): string
    {
        return $this->text->toString();
    }

    public function isMustAlign(): bool
    {
        return $this->mustAlign;
    }
}
