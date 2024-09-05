<?php
namespace Benjaminzwahlen\Brace\common;

use Benjaminzwahlen\Brace\common\exceptions\ConfigFileNotFoundException;

class Config
{

    private static string $defaultEnv = "default";

    private static function loadVars($path)
    {
        if (file_exists($path)) {
            $raw = yaml_parse_file($path);
            return $raw ? $raw : array();
        } else
            throw new ConfigFileNotFoundException("Can't find: " . $path);
    }

    public static function load($env, $configPath): array
    {
        $defaultValues = Config::loadVars(str_replace("{env}", Config::$defaultEnv, $configPath));


        $envValues = Config::loadVars(str_replace("{env}", $env, $configPath));

        $values = array_replace_recursive($defaultValues, $envValues);

        $values["current_time"] = time();
        $values["current_date"] = date("Y-m-d H:i:s", $values["current_time"]);
        $values["current_date_simple"] = date("Y-m-d", $values["current_time"]);

        return $values;

    }
}
