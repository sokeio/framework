<?php

namespace Sokeio;

use Illuminate\Support\Facades\Log;

class WatchTime
{
    private static $startTime = 0;
    public static function get()
    {
        return microtime(true);
    }
    public static function start()
    {
        self::$startTime = microtime(true);
    }
    public static function end()
    {
        return microtime(true) - self::$startTime;
    }
    public static function reset()
    {
        self::$startTime = 0;
    }
    public static function logTime($reset = false, $key = 'watchTime', $tracking = false)
    {
        if ($tracking) {
            $debug_tracking = debug_backtrace()[1];
            Log::debug($key . '::file-' . $debug_tracking['file'] . '::line-' . $debug_tracking['line']);
        }
        $time = self::end();
        if ($reset) {
            self::start();
        }
        Log::debug($key . '::time-' . round($time, 4));
    }
}
