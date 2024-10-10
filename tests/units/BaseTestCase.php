<?php

namespace Tests\Units;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        set_error_handler(function ($errno, $errstr, $errFile, $errLine) {
            throw new \RuntimeException($errstr . ' on line ' . $errLine . ' in file ' . $errFile);
        });
    }

    public static function tearDownAfterClass(): void
    {
        restore_error_handler();
    }
}
