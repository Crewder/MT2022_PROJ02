<?php

namespace App\Controller;
require_once __DIR__ . '../../vendor/autoload.php';

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQHandler
{
    private AMQPChannel $AMQPChannel;

    public function __construct(RabbitMqConfig $mqconfig)
    {
        $this->AMQPChannel = $mqconfig->Connection();
    }

    public function SendMessage($message): void
    {
        if (empty($message)) {
            $message = "le message est vide";
        }

        $FormattedMessage = new AMQPMessage(
            $message,
            array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
        );

        $this->AMQPChannel->basic_publish($FormattedMessage, '', 'hello');
    }

    public function ListenQueue($queueName): void
    {
        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
            sleep(substr_count($msg->body, '.'));
            echo " [x] Done\n";
            $msg->ack();
        };

        $this->AMQPChannel->basic_qos(null, 1, null);
        $this->AMQPChannel->basic_consume($queueName, false, true, false, false, $callback);
    }


}