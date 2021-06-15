<?php

namespace App\Controller;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQHandler
{
    private AMQPChannel $_AMQPChannel;
    private string $_exchange;
    private string $_routing_key;

    public function __construct(RabbitMqConfig $config)
    {
        $this->_AMQPChannel = $config->GetChannel();
        $this->_exchange = $config->GetExchange();
        $this->_routing_key = $config->GetRoutingKey();
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

        $this->_AMQPChannel->basic_publish($FormattedMessage, $this->_exchange, $this->_routing_key);
    }

    /**
     * Listen Queue and consume message.
     *
     * @param $queueName
     * @return void
     */
    public function ListenQueue($queueName) : void
    {
        $callback = function ($msg) {
            $msg->ack();
        };

        $this->_AMQPChannel->basic_qos(null, 1, null);
        $this->_AMQPChannel->basic_consume($queueName, 'avatar', false, true, false, $callback);
    }
}