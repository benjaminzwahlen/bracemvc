<?php

namespace benjaminzwahlen\bracemvc\messagequeues;

use benjaminzwahlen\bracemvc\messagequeues\MessageQueueInterface as MessagequeuesMessageQueueInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ implements MessagequeuesMessageQueueInterface
{
    public static string $host;
    public static string $port;
    public static string $username;
    public static string $password;

    public static bool $passive = false;
    public static bool $durable = false;
    public static bool $exclusive = false;
    public static bool $autoDelete = true;


    public static function init($host_, $port_, $username_, $password_)
    {
        RabbitMQ::$host = $host_;
        RabbitMQ::$port = $port_;
        RabbitMQ::$username = $username_;
        RabbitMQ::$password = $password_;
    }


    public static function send(string $queueName, AbstractTaskMessage $data)
    {
        $connection = new AMQPStreamConnection(RabbitMQ::$host, RabbitMQ::$port, RabbitMQ::$username, RabbitMQ::$password);

        $channel = $connection->channel();

        $channel->queue_declare(
            $queueName,
            RabbitMQ::$passive,
            RabbitMQ::$durable,
            RabbitMQ::$exclusive,
            RabbitMQ::$autoDelete
        );

        $msg = new AMQPMessage(
            json_encode($data),
            array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
        );

        $channel->basic_publish($msg, '', $queueName);

        $channel->close();
        $connection->close();
    }
}
