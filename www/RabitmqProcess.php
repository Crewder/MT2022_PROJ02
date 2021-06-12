<?php

use App\Controller\RabbitMqConfig;
use App\Controller\RabbitMQHandler;

$host = "localhost";
$port = 5672;
$user = "guest";
$password = "guest";
$queueName = "Image-Resizer";
$message = "hello-world";

$config = new RabbitMqConfig($host,$port,$user,$password,$queueName);
$config->CreatingQueue();
$queue = $config->GetQueueName();

$handle = new RabbitMQHandler($config,);
$handle->SendMessage($message);
$handle->ListenQueue($queue);

$config->CloseChannel();
