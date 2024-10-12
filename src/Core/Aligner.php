<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule\Core;

class Aligner
{
    public static function initFromToken(TextToken ...$text): self
    {
        return new Aligner($text);
    }

    /**
     * @param array<TextToken> $alignableList
     */
    public function __construct(private array $alignableList)
    {
    }

    public function align(): self
    {
        $max = $this->getMaxLettersNumber();

        $result = array_map(function (TextToken $item) use ($max) {
            return $item->fillIn($max);
        }, $this->alignableList);

        return new self($result);
    }

    public function getMaxLettersNumber(): int
    {
        $textsLength = array_map(function (TextToken $item): int {
            return $item->length();
        }, $this->alignableList);

        return (int) max($textsLength);
    }

    public function replaceToTokenTable(TextTokenTable $table): TextTokenTable
    {
        foreach ($this->alignableList as $token) {
            $table = $table->updateToken($token);
        }

        return $table;
    }

    /**
     * @param array<string> $texts
     */
    public function hasTexts(array $texts): bool
    {
        foreach ($this->alignableList as $key => $token) {
            if (!$token->hasText($texts[$key])) {
                return false;
            }
        }

        return true;
    }
}
