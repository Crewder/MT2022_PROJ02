<?php

namespace App\Controller;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQHandler
{


    private AMQPChannel $_AMQPChannel;

    public function __construct(RabbitMqConfig $config)
    {
        $this->_AMQPChannel = $config->GetChannel();
    }

    public function SendMessage($message): void
    {
        if (empty($message)) {
            $message = "le message est vide";
        }

        $FormattedMessage = new AMQPMessage(
            $message,
        );


        $msg = new AMQPMessage('Hello World!');
        $this->_AMQPChannel->basic_publish($msg, '', 'avatar');

      //  $this->_AMQPChannel->basic_publish($FormattedMessage);
    }

    public function ListenQueue($queueName): void
    {
        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
            sleep(substr_count($msg->body, '.'));
            echo " [x] Done\n";
            $msg->ack();
        };

       // $this->_AMQPChannel->basic_qos(null, 1, null);
        $this->_AMQPChannel->basic_consume($queueName, 'avatar', false, true, false, $callback);
    }


}