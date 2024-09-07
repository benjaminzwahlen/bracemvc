<?php

namespace benjaminzwahlen\bracemvc\common;

class Config
{
    public static array $config;

    public static function load($envVars)
    {
        Config::$config = array_merge([], $envVars);
        Config::$config["current_time"] = time();
        Config::$config["current_date"] = date("Y-m-d H:i:s", Config::$config["current_time"]);
        Config::$config["current_date_simple"] = date("Y-m-d", Config::$config["current_time"]);
    }

    public static function get($key): ?string
    {
        return Config::$config[$key] ?? null;
    }
}
