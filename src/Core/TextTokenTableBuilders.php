<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule\Core;

class TextTokenTableBuilders
{
    public static function init(): self
    {
        return new TextTokenTableBuilders([TextTokenTableBuilder::init()]);
    }

    /**
     * @param array<TextTokenTableBuilder> $builders
     */
    public function __construct(public array $builders)
    {
    }

    public function obtainForNewPropertyBlock(): self
    {
        $this->builders[] = TextTokenTableBuilder::init();

        return $this;
    }

    public function addColumn(int $index, string $text): self
    {
        $builder = $this->getLastBuilder()->addColumn($index, $text);
        $this->updateLastBuilder($builder);

        return $this;
    }

    public function addNewRow(int $index, string $text): self
    {
        $builder = $this->getLastBuilder()->addNewRow($index, $text);
        $this->updateLastBuilder($builder);

        return $this;
    }

    public function isNonePropertyToken(): bool
    {
        return $this->builders[0]->isEmpty();
    }

    public function isFirstInserting(): bool
    {
        return $this->getLastBuilder()->isEmpty();
    }

    /**
     * @return array<TextTokenTable>
     */
    public function build(): array
    {
        $list = array_filter($this->builders, fn ($builder) => !$builder->isEmpty());

        return array_map(fn ($builder) => $builder->build(), $list);
    }

    private function getLastBuilder(): TextTokenTableBuilder
    {
        return $this->builders[count($this->builders) - 1];
    }

    private function updateLastBuilder(TextTokenTableBuilder $builder): void
    {
        $this->builders[count($this->builders) - 1] = $builder;
    }
}
