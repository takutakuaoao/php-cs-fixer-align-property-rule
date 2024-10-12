<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule\Core;

class Formatter
{
    /**
     * @param array<array<string>> $rows
     */
    public static function init(array $rows): self
    {
        return new self(Analyzer::init($rows));
    }

    public function __construct(private Analyzer $analyzer)
    {
    }

    /**
     * @return array<array<string>>
     */
    public function format(): array
    {
        $tokenTable = $this->analyzer->markAsRequiringAlign();
        $chunk      = $tokenTable->chunkVerticalAll();

        foreach ($chunk as $aChunk) {
            $tokenTable = Aligner::initFromToken(...$aChunk)
                ->align()
                ->replaceToTokenTable($tokenTable);
        }

        return $tokenTable->toPlain();
    }
}
