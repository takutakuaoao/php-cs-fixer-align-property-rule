<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule\Core;

class Text
{
    public static function init(string $text): self
    {
        return new Text($text);
    }

    public function __construct(private string $text)
    {
    }

    public function length(): int
    {
        return mb_strlen($this->text);
    }

    public function fillIn(int $maxLettersNumber, string $space = ' '): Text
    {
        $result = mb_str_pad($this->text, $maxLettersNumber, $space);

        return new Text($result);
    }

    public function equal(Text $other): bool
    {
        return $this->text === $other->text;
    }

    public function toString(): string
    {
        return $this->text;
    }
}
