<?php
namespace benjaminzwahlen\bracemvc\common\storage\session;


interface AbstractSessionVar
{
    public static function getKey(): string;
}
