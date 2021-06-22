<?php

namespace App\Controller;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQHandler
{
    private AMQPChannel $amqpChannel;
    private string $exchange;
    private string $routing_key;
    private RabbitMqConfig $config;

    public function __construct(RabbitMqConfig $config)
    {
        $this->amqpChannel = $config->GetChannel();
        $this->exchange = $config->GetExchange();
        $this->routing_key = $config->GetRoutingKey();
        $this->config = $config;
    }

    /**
     * Send a message to the exchange with routing key.
     * The Exchange will dispatch the message on the right queue.
     *
     * @param $message
     * @return void
     */
    public function SendMessage($message): void
    {
        if (empty($message)) {
            $message = "le message est vide";
        }
        $FormattedMessage = new AMQPMessage(
            $message,
        );

        $this->amqpChannel->basic_publish($FormattedMessage, $this->exchange, $this->routing_key);
    }

    /**
     * Listen Queue and consume message.
     *
     * @param $queueName
     * @return void
     * @throws \ErrorException
     */
    public function ListenQueue($queueName): void
    {
        $callback = function ($msg){
            $controller = new ProcessPictureController($this->config);
            $controller->processPicture($msg->body);
        };

        $this->amqpChannel->basic_qos(null, 1, null);
        $this->amqpChannel->basic_consume(
            $queueName,
            'avatar',
            false,
            true,
            false,
            $callback
        );

        while ($this->config->GetChannel()->is_open()) {
            $this->config->GetChannel()->wait();
        }
    }
}