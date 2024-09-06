<?php
namespace benjaminzwahlen\bracemvc\common\exceptions;

class MethodNotAllowedException extends \Exception
{

    // Redefine the exception so message isn't optional
    public function __construct($message)
    {
        // make sure everything is assigned properly
        parent::__construct($message);
    }

    // custom string representation of object
    public function __toString()
    {
        return __CLASS__ . ": [{$this->message}]\n";
    }
}
