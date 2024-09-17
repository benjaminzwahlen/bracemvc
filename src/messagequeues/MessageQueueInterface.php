<?php

use benjaminzwahlen\bracemvc\messagequeues\AbstractTaskMessage;

interface MessageQueueInterface
{
    public static function send(string $queueName, AbstractTaskMessage $data);
}
