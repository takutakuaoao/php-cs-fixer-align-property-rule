<?php

namespace Tests\Units;

use PhpCsFixerAlignPropertyRule\Core\TablePosition;
use PhpCsFixerAlignPropertyRule\Core\TextTokenTableBuilder;

class TextTokenTableBuilderTest extends BaseTestCase
{
    private TextTokenTableBuilder $builder;

    public function setUp(): void
    {
        $this->builder = TextTokenTableBuilder::init();
    }

    public function test_build(): void
    {
        $result = $this->builder->addColumn(0, 'text1-1')->build();

        $target = $result->findToken(TablePosition::fromArray(['row' => 0, 'column' => 0]));
        $this->assertTrue($target?->hasText('text1-1'));
    }

    public function test_building_by_processing_position(): void
    {
        $result = $this->builder->addColumn(0, 'text-1-1')->addColumn(1, 'text-1-2')->build();

        $target = $result->findToken(TablePosition::fromArray(['row' => 0, 'column' => 1]));
        $this->assertTrue($target?->hasText('text-1-2'));
    }

    public function test_adding_a_row_and_building(): void
    {
        $result = $this->builder
            ->addColumn(0, 'text-1-1')
            ->addColumn(1, 'text-1-2')
            ->addNewRow(2, 'text-2-1')
            ->build();

        $target = $result->findToken(TablePosition::fromArray(['row' => 1, 'column' => 0]));
        $this->assertTrue($target?->hasText('text-2-1'));
    }
}
