<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// todo extraire la création de la queue (pas besoin de créer la queue pour ecouter) et lancer lors du lancement du docker ?
$channel->queue_declare('Image-Resizer', false, false, false, false);

$callback = function ($message) {
    echo $message->body;

};

$channel->basic_consume('Image-Resizer', '', false, true, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();
