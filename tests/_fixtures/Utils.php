<?php

declare(strict_types=1);

namespace Tests\Fixtures;

class Utils
{
    /**
     * @param array<mixed> $arr
     */
    public static function debugArray(array $arr): void
    {
        $debug = json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        var_dump("\n\n" . '####################################################' . "\n\n" . $debug);
    }
}
