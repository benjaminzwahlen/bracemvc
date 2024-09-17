<?php

namespace benjaminzwahlen\bracemvc\messagequeues;

use benjaminzwahlen\bracemvc\messagequeues\AbstractTaskMessage;

interface MessageQueueInterface
{
    public static function send(string $queueName, AbstractTaskMessage $data);

    public static function registerWorker(string $queueName, callable $callback);
}
