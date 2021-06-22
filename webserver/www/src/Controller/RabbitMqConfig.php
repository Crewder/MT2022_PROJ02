<?php

namespace App\Controller;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMqConfig
{
    private string $host;
    private int $port;
    private string $user;
    private string $password;
    private string $queueName;
    private string $vhost;
    public string $fileserverhost;
    private AMQPChannel $amqpChannel;
    private string $exchange;
    private string $routing_key;


    public function __construct(
        string $host,
        int $port,
        string $user,
        string $password,
        string $queueName,
        string $vhost,
        string $fileserverhost,
        string $exchange,
        string $routing_key
    )
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->queueName = $queueName;
        $this->vhost = $vhost;
        $this->fileserverhost = $fileserverhost;
        $this->exchange = $exchange;
        $this->routing_key = $routing_key;
    }

    /**
     * Creating the Stream I/O connections to rabbitMQ Server.
     *
     * @return void
     */
    public function Connection(): void
    {
        $amqpStreamConnection = new AMQPStreamConnection(
            $this->host,
            $this->port,
            $this->user,
            $this->password,
            $this->vhost
        );
        $this->amqpChannel = $amqpStreamConnection->channel();
    }

    /**
     * Creating A queue on rabbitMQ Server.
     *
     * @return void
     */
    public function CreateQueue(): void
    {
        $this->amqpChannel->queue_declare(
            $this->queueName,
            false,
            false,
            false,
            false
        );
    }

    /**
     * Creating an Exchange on rabbitMQ Server.
     * Exchange type : "direct", topic","headers","fanout"
     *
     * @param string $type
     * @return void
     */
    public function CreateExchange(string $type): void
    {
        $this->amqpChannel->exchange_declare(
            $this->exchange,
            $type,
            false,
            false,
            false
        );
    }

    /**
     * Binding an Exchange to a queue.
     * Message received by the Exchange will transited to the Queue
     *
     * @param string $queue
     * @param string $exchange
     * @return void
     */
    public function BindExchangeToQueue(string $queue, string $exchange): void
    {
        $this->amqpChannel->queue_bind($queue, $exchange, $this->routing_key);
    }

    /**
     * Get the avaible routing key on queue.
     *
     * @return string
     */
    public function GetRoutingKey(): string
    {
        return $this->routing_key;
    }

    /**
     * Get the name of the avaible queues.
     *
     * @return string
     */
    public function GetQueueName(): string
    {
        return $this->queueName;
    }

    /**
     * Get the avaible exchange.
     *
     * @return string
     */
    public function GetExchange(): string
    {
        return $this->exchange;
    }

    /**
     * Get the avaible channel.
     *
     * @return AMQPChannel
     */
    public function GetChannel(): AMQPChannel
    {
        return $this->amqpChannel;
    }

    /**
     * handle the closure of the active AMQPChannel
     *
     * @return AMQPChannel
     */
    public function CloseChannel(): AMQPChannel
    {
        return $this->amqpChannel->close();
    }
}