<?php
namespace Benjaminzwahlen\Brace;

require 'vendor/autoload.php';

class Test {


    public static function go()
    {
        (new A())->out("Hello World");
    }
}