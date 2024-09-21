<?php

namespace benjaminzwahlen\bracemvc\common\storage\database;

use benjaminzwahlen\bracemvc\common\exceptions\DatabaseConnectionException;

class DB
{
    public static $db = null;
    private static string $host;
    private static string $user;
    private static string $password;
    private static string $dbName;

    public static function init($host, $user,  #[\SensitiveParameter] $password, $dbName)
    {
        try {
            // Create connection
            DB::$db = new \mysqli($host, $user, $password, $dbName);
            DB::$host = $host;
            DB::$user = $user;
            DB::$password = $password;
            DB::$dbName = $dbName;

        } catch (\Exception $e) {
            throw new DatabaseConnectionException("Unable to connect to database: " . $e->getMessage());
        }
        // Check connection
        if (DB::$db->connect_error) {
            throw new DatabaseConnectionException("Unable to connect to database: " . DB::$db->connect_error);
        }
    }

    public static function refreshConnection()
    {
        if( !\mysqli_ping(DB::$db) ) DB::$db = new \mysqli(DB::$host, DB::$user, DB::$password, DB::$dbName);
    }
}
