<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule;

class Analyzer
{
    /**
     * @param array<array<string>> $rows
     */
    public static function init(array $rows): self
    {
        return new self(TextTokenTable::init($rows));
    }

    public function __construct(private TextTokenTable $table)
    {
    }

    public function markAsRequiringAlign(): TextTokenTable
    {
        $this->table->loop(function (TablePosition $current, TextToken $token) {
            if ($this->isMarkAsRequiredAlign($current)) {
                $this->table = $this->table->updateToken($token->markAsRequiredAlign());
            }
        });

        return $this->table;
    }

    private function isMarkAsRequiredAlign(TablePosition $position): bool
    {
        return $this->table->findToken($position->right())
            && (
                $this->table->findToken($position->bottom())
                || $this->table->findToken($position->top())
            );
    }
}
