<?php

namespace Tests\Units;

use PhpCsFixerAlignPropertyRule\Core\Formatter;
use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    public function test_align_format(): void
    {
        $formatter = Formatter::init([
            ['private', 'string', '$stringProperty'],
            ['public', 'int', '$intProperty'],
            ['protected', 'bool', '$boolProperty'],
        ]);

        $result = $formatter->format();

        $this->assertEquals([
            ['private  ', 'string', '$stringProperty'],
            ['public   ', 'int   ', '$intProperty'],
            ['protected', 'bool  ', '$boolProperty'],
        ], $result);
    }
}
