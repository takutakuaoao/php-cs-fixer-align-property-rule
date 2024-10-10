<?php

namespace Tests\Units;

use PhpCsFixerAlignPropertyRule\TextToken;
use PhpCsFixerAlignPropertyRule\TextTokenTable;
use PHPUnit\Framework\TestCase;

class TextTokenTableTest extends TestCase
{
    private TextTokenTable $table;

    public function setUp(): void
    {
        $this->table = TextTokenTable::init([
            ['test'],
            ['test', 'chunk-1-1'],
            ['test'],
            ['test', 'chunk-2-1'],
            ['test', 'chunk-2-2'],
            ['test'],
        ]);
    }

    public function test_chunk_vertical(): void
    {
        $chunk = $this->table->chunkVertical(1);

        $this->assertChunk($chunk[0], ['chunk-1-1']);
        $this->assertChunk($chunk[1], ['chunk-2-1', 'chunk-2-2']);
    }

    public function test_no_chunk_vertical(): void
    {
        $chunk = $this->table->chunkVertical(2);

        $this->assertEquals($chunk, []);
    }

    public function test_get_all_chunk_vertical(): void
    {
        $table = TextTokenTable::init([
            ['chunk-1-1', 'chunk-2-1'],
            ['chunk-1-2', 'chunk-2-2', 'chunk-4-1'],
            ['chunk-1-3'],
            ['chunk-1-4', 'chunk-3-1'],
        ]);
        $chunk = $table->chunkVerticalAll();

        $this->assertChunk($chunk[0], ['chunk-1-1', 'chunk-1-2', 'chunk-1-3', 'chunk-1-4']);
        $this->assertChunk($chunk[1], ['chunk-2-1', 'chunk-2-2']);
        $this->assertChunk($chunk[2], ['chunk-3-1']);
        $this->assertChunk($chunk[3], ['chunk-4-1']);
    }

    /**
     * @param array<TextToken> $actual
     * @param array<string>    $expected
     */
    private function assertChunk(array $actual, array $expected): void
    {
        $this->assertEquals(count($expected), count($actual));

        foreach ($actual as $key => $item) {
            self::assertTrue($item->hasText($expected[$key]));
        }
    }
}
