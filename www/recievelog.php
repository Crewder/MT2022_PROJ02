<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Controller\ProcessPictureController;
use App\Controller\RabbitMqConfig;
use PhpAmqpLib\Connection\AMQPStreamConnection;


$host = "rabbitmq";
$port = 5672;
$user = "guest";
$password = "guest";
$vhost = "rabbitmq-host";

//confiiguration des queues
$queuename = "Image-Resizer";
$exchange = "direct_resize";
$routingkey = "Resize_route";

// Connection au server et creation des elements necessaire a  l'utilisation des queues
$config = new RabbitMqConfig(
    $host,
    $port,
    $user,
    $password,
    $queuename,
    $vhost,
    $exchange,
    $routingkey
);
$config->Connection();
$config->CreateQueue();
$config->CreateExchange("direct");

// Private variable dans l'objet $config
$Queue = $config->GetQueueName();
$Exchange = $config->GetExchange();

//relie l'exchange a la queue
$config->BindExchangeToQueue($Queue, $Exchange);


echo " [*] Waiting for logs. To exit press CTRL+C\n";

$callback = function ($msg) use ($config) {
    $controller = new ProcessPictureController($config);
    $controller->processPicture($msg->body);
};


$config->GetChannel()->basic_consume(
    "Image-Resizer",
    'avatar',
    false,
    true,
    false,
    false,
    $callback
);

while ($config->GetChannel()->is_open()) {
    $config->GetChannel()->wait();
}

