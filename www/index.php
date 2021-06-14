<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php

require_once 'vendor/autoload.php';

use App\Controller\RabbitMqConfig;
use App\Controller\RabbitMQHandler;
use App\Model\User\UserModel;

$user = new UserModel();
var_dump($user->getAvatar(2));
$user->setAvatar(2, 2);
var_dump($user->getAvatar(2));

$host = "172.18.0.1";
$port = 5672;
$user = "guest";
$password = "guest";
$queueName = "Image-Resizer";
$message = "hello-world2";
$vhost= "rabbitmq-host";

$config = new RabbitMqConfig($host,$port,$user,$password,$queueName,$vhost);
$config->Connection();
$config->CreatingQueue();

$handler = new RabbitMQHandler($config);
$handler->SendMessage($message);
//var_dump($config->GetQueueName()));

//$handler->ListenQueue($Queue);



?>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="picture" id="picture">
        <input type="submit" value="Enregistrer image">
    </form>
</body>
</html>