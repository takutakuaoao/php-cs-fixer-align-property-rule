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
        public readonly bool $mustAlign,
        private readonly TablePosition $originalPosition,
    ) {
    }

    public function markAsRequiredAlign(): self
    {
        return new self($this->text, true, $this->originalPosition);
    }

    public function hasText(string $text): bool
    {
        return $this->text->equal(Text::init($text));
    }
}
