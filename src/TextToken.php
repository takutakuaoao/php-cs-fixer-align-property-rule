<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule;

class TextToken
{
    /**
     * @param array{row: int, column: int} $position
     */
    public static function init(string $text, array $position): self
    {
        return new self(Text::init($text), false, TablePosition::fromArray($position));
    }

    public function __construct(
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
        return new self($this->text, true, $this->originalPosition);
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
