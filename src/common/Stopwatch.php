<?php

namespace benjaminzwahlen\bracemvc\common;

use RuntimeException;

class Stopwatch
{
    private static ?int $start = null;

    public static function start(): void
    {
        self::$start = hrtime(true);
    }

    public static function stop(): string
    {
        if (self::$start === null) {
            throw new RuntimeException('Timer has not been started.');
        }

        $elapsedNs = hrtime(true) - self::$start;
        self::$start = null;

        $milliseconds = $elapsedNs / 1_000_000;

        if ($milliseconds < 1000) {
            return round($milliseconds) . 'ms';
        }

        return number_format($milliseconds / 1000, 3, '.', '') . ' sec';
    }
}