<?php

namespace benjaminzwahlen\bracemvc\common\storage\database;

use benjaminzwahlen\bracemvc\common\exceptions\DatabaseConnectionException;

class DB
{
    public static $db = null;

    public static function init($host, $user,  #[\SensitiveParameter] $password, $dbname)
    {
        try {
            // Create connection
            DB::$db = new \mysqli($host, $user, $password, $dbname);
        } catch (\Exception $e) {
            throw new DatabaseConnectionException("Unable to connect to database: " . $e->getMessage());
        }
        // Check connection
        if (DB::$db->connect_error) {
            throw new DatabaseConnectionException("Unable to connect to database: " . DB::$db->connect_error);
        }
    }
}
