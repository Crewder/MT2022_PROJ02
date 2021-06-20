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

use App\Controller\ProcessPictureController;
use App\Controller\RabbitMqConfig;
use App\Controller\RabbitMQHandler;

$host = "rabbitmq";
$port = 5672;
$user = "guest";
$password = "guest";
$vhost = "rabbitmq-host";

//configuration des queues
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

//handler pour la gestion des message  / Listen / Send
$handler = new RabbitMQHandler($config);
$handler->ListenQueue($Queue);

// Fermeture de la connection
//$config->CloseChannel();

if ($_FILES['picture']) {
    $controller = new ProcessPictureController($config);
    try {
        $controller->uploadPicture();
    } catch (Exception $e) {
        echo $e;
    }
    ?>

    <a type="button" href="/"> Retour </a>
    <?php
}else {
?>
<form action="index.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="picture" id="picture">
    <input type="submit" value="Enregistrer image">
</form>
</body>
</html>

<?php
}
