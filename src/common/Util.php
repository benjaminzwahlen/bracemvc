<?php

namespace benjaminzwahlen\bracemvc\common;

class Util
{

    /**
     * Pass through the $_SERVER array. Function will check for the HTTP_X_REQUESTED_WITH key
     *
     */
    public static function isAjaxRequest(array $server): bool
    {
        return  isset($server['HTTP_X_REQUESTED_WITH']) and strtolower($server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public static function esc($str): string
    {
        if (is_null($str))
            return "";
        return htmlspecialchars($str);
    }

    public static function getSimpleToken($length): string
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHJKMNPQRSTUVWXYZ";
        $codeAlphabet .= "23456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[Util::cryptoRandSecure(0, $max)];
        }
        return $token;
    }

    public static function getToken($length): string
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[Util::cryptoRandSecure(0, $max)];
        }
        return $token;
    }

    private static function cryptoRandSecure($min, $max): string
    {
        $range = $max - $min;
        if ($range < 1) {
            return $min; // not so random...
        }
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }
}
