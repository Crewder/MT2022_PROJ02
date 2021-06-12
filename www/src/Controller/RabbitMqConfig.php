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
    public AMQPChannel $AMQPChannel;

    public function __construct(string $host, int $port, string $user, string $password, string $queueName)
    {
        $this->_host = $host;
        $this->_port = $port;
        $this->_user = $user;
        $this->_password = $password;
        $this->_queueName = $queueName;
    }

    public function CreatingQueue(): void
    {
        $this->AMQPChannel->queue_declare($this->_queueName, false, true, false, false);
    }

    public function Connection(): AMQPChannel
    {
        $AMQPStreamConnection = new AMQPStreamConnection($this->_host, $this->_queueName, $this->_port, $this->_user, $this->_password);
        return $AMQPStreamConnection->channel();
    }

    public function CloseChannel()
    {
        return $this->AMQPChannel->close();
    }

    public function GetQueueName() :string {
        return $this->_queueName;
    }
}