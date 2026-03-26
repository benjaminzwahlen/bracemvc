<?php

namespace benjaminzwahlen\bracemvc\common;

final class RequestTimer
{
    private static array $marks = [];
    private static float $requestStart = 0.0;
    private static bool $enabled = false;

    public static function start(float $floatTime): void
    {
        self::$enabled = true;
        self::$requestStart = $floatTime;
        self::$marks = ['php_request_start' => self::$requestStart];
    }

    public static function mark(string $label): void
    {
        if (!self::$enabled) {
            return; // silently ignore if not enabled
        }

        self::$marks[$label] = microtime(true);
    }

    public static function report(): array
    {
        if (!self::$enabled) {
            return [];
        }

        $result = [];
        $prev = self::$requestStart;

        foreach (self::$marks as $label => $time) {
            $result[$label] = round($time - $prev, 5);
            $prev = $time;
        }

        $result['total'] = round(microtime(true) - self::$requestStart, 5);

        return $result;
    }

    public static function outputHeader(): void
    {
        if (!self::$enabled) {
            return;
        }
        header('X-Debug-Timing: ' . json_encode(self::report()));
    }
}
