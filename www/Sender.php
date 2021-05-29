<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('Image-Resizer', false, true, false, false);

// todo a remplacer par l'image
$image= "hello world";
if (empty($image)) {
    $image = "l'image est vide";
}

$message = new AMQPMessage(
    $image
// Options
);

$channel->basic_publish($message, '', 'hello');

$channel->close();
$connection->close();
?>