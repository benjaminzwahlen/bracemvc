<?php

namespace benjaminzwahlen\bracemvc\messagequeues;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ implements MessageQueueInterface
{
    public static string $host;
    public static string $port;
    public static string $username;
    public static string $password;

    public static bool $passive = false;
    public static bool $durable = true;
    public static bool $exclusive = false;
    public static bool $autoDelete = false;


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
            serialize($data),
            array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
        );

        $channel->basic_publish($msg, '', $queueName);

        $channel->close();
        $connection->close();
    }


    public static function registerWorker(string $queueName, callable $userCallback)
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


        $localCallback = function ($msg) use ($userCallback) {
            $task = unserialize($msg->getBody());
            if (true === call_user_func($userCallback, $task))
                $msg->ack();
        };

        $channel->basic_consume($queueName, '', false, false, false, false, $localCallback);

        try {
            $channel->consume();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }

        $channel->close();
        $connection->close();
    }
}
