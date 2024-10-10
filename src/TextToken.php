<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule;

class TextToken
{
    public static function init(string $text): self
    {
        return new self(Text::init($text), false);
    }

    public function __construct(private Text $text, public readonly bool $mustAlign)
    {
    }

    public function markAsRequiredAlign(): self
    {
        return new self($this->text, true);
    }
}
