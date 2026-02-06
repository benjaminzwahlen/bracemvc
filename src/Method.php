<?php

namespace benjaminzwahlen\bracemvc;

enum Method: string
{
    case GET = 'GET';
    case HEAD = 'HEAD';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
    case CONNECT = 'CONNECT';
    case OPTIONS = 'OPTIONS';
    case TRACE = 'TRACE';
    case PATCH = 'PATCH';

    public static function fromName(string $name): Method
    {
        $method = self::tryFromName($name);

        if (is_null($method)) {
            $enumName = static::class;
            throw new \ValueError("$name is not a valid name for enum \"$enumName\"");
        }

        return $method;
    }

    private static function tryFromName(?string $name): ?Method
    {
        // We aren't allowed to pass a null value to strtoupper(), so we have to handle this early.
        if (is_null($name)) {
            return null;
        }

        $name = strtoupper($name);

        if (defined("self::$name")) {
            /**
             * @var Method
             */
            $enumCase = constant("self::$name");
            return $enumCase;
        }

        return null;
    }
}