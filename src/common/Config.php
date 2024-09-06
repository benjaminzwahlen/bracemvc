<?php

namespace benjaminzwahlen\bracemvc\common;

class Config
{
    public static function load($envVars): array
    {
        $values = [];
        foreach ($envVars as $key => $value) {
            $keyparts = explode(".", $key);
            if (!array_key_exists($keyparts[0], $values))
                $values[$keyparts[0]] = [];
            $values[$keyparts[0]][$keyparts[1]] = $value;
        }

        $values["current_time"] = time();
        $values["current_date"] = date("Y-m-d H:i:s", $values["current_time"]);
        $values["current_date_simple"] = date("Y-m-d", $values["current_time"]);

        return $values;
    }
}
