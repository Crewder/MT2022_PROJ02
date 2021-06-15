<?php

namespace App\Controller;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMqConfig
{
    private string $_host;
    private int $_port;
    private string $_user;
    private string $_password;
    private string $_queueName;
    private string $_vhost;
    private AMQPChannel $_AMQPChannel;
    private string $_exchange;
    private string $_routing_key;


    public function __construct(string $_host, int $_port, string $_user, string $_password, string $_queueName, string $_vhost, string $_exchange, string $_routing_key)
    {
        $this->_host = $_host;
        $this->_port = $_port;
        $this->_user = $_user;
        $this->_password = $_password;
        $this->_queueName = $_queueName;
        $this->_vhost = $_vhost;
        $this->_exchange = $_exchange;
        $this->_routing_key = $_routing_key;
    }

    /**
     * Creating the Stream I/O connections to rabbitMQ Server.
     *
     * @return void
     */
    public function Connection()
    {
        $AMQPStreamConnection = new AMQPStreamConnection($this->_host, $this->_port, $this->_user, $this->_password, $this->_vhost);
        $this->_AMQPChannel = $AMQPStreamConnection->channel();
    }

    /**
     * Creating A queue on rabbitMQ Server.
     *
     * @return void
     */
    public function CreateQueue(): void
    {
        $this->_AMQPChannel->queue_declare($this->_queueName, false, false, false, false);
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
        $this->_AMQPChannel->exchange_declare($this->_exchange, $type, false, false, false);
    }

    /**
     * Binding an Exchange to a queue.
     * Message received by the Exchange will transited to the Queue
     *
     * @param string $queue
     * @param string $exchange
     * @return void
     */
    public function BindExchangeToQueue(string $queue,string $exchange): void
    {
        $this->_AMQPChannel->queue_bind($queue, $exchange, $this->_routing_key);
    }

    /**
     * Get the avaible routing key on queue.
     *
     * @return string
     */
    public function GetRoutingKey(): string
    {
        return $this->_routing_key;
    }

    /**
     * Get the name of the avaible queues.
     *
     * @return string
     */
    public function GetQueueName(): string
    {
        return $this->_queueName;
    }

    /**
     * Get the avaible exchange.
     *
     * @return string
     */
    public function GetExchange(): string
    {
        return $this->_exchange;
    }

    /**
     * Get the avaible exchange.
     *
     * @return AMQPChannel
     */
    public function GetChannel(): AMQPChannel
    {
        return $this->_AMQPChannel;
    }

    /**
     * handle the closure of the active AMQPChannel
     *
     * @return AMQPChannel
     */
    public function CloseChannel() :AMQPChannel
    {
        return $this->_AMQPChannel->close();
    }
}