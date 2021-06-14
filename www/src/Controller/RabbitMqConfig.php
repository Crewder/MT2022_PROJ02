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

    public function __construct(string $host, int $port, string $user, string $password, string $queueName,string $_vhost)
    {
        $this->_host = $host;
        $this->_port = $port;
        $this->_user = $user;
        $this->_password = $password;
        $this->_queueName = $queueName;
        $this->_vhost= $_vhost;
    }

    public function CreatingQueue(): void
    {
        $this->_AMQPChannel->queue_declare($this->_queueName, false, false, false, false);
    }

    public function Connection()
    {
        $AMQPStreamConnection = new AMQPStreamConnection($this->_host,$this->_port,$this->_user,$this->_password,$this->_vhost);
        $this->_AMQPChannel = $AMQPStreamConnection->channel();
    }

    public function CloseChannel()
    {
        return $this->_AMQPChannel->close();
    }

    public function GetQueueName(): string
    {
        return $this->_queueName;
    }

    public function GetChannel(): AMQPChannel
    {
        return $this->_AMQPChannel;
    }
}