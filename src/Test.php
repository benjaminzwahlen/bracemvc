<?php
namespace Benjaminzwahlen\Brace;

use Benjaminzwahlen\Brace\boo\A;

require 'vendor/autoload.php';

class Test {


    public static function go()
    {
        (new A())->out("Hello World");
    }
}