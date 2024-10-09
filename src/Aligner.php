<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule;

class Aligner
{
    public static function init(string ...$text): self
    {
        return new Aligner(array_map(function ($item) {
            return Text::init($item);
        }, $text));
    }

    /**
     * @param array<Text> $textList
     */
    public function __construct(private array $textList)
    {
    }

    public function align(): self
    {
        $max = $this->getMaxLettersNumber();

        $result = array_map(function (Text $item) use ($max) {
            return $item->fillIn($max);
        }, $this->textList);

        return new self($result);
    }

    public function getMaxLettersNumber(): int
    {
        $textsLength = array_map(function (Text $item): int {
            return $item->length();
        }, $this->textList);

        return (int) max($textsLength);
    }

    public function equal(self $other): bool
    {
        if ($this->size() !== $other->size()) {
            return false;
        }

        for ($i = 0; $i < $this->size(); ++$i) {
            if (!$this->textList[$i]->equal($other->textList[$i])) {
                return false;
            }
        }

        return true;
    }

    private function size(): int
    {
        return count($this->textList);
    }
}
