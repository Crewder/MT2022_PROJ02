#!/bin/bash

printf "Installation du projet\n"
docker-compose build
docker-compose up -d

docker exec -ti webserver /bin/bash -c "composer install;composer dump-autoload"
docker exec -ti listenserver /bin/bash -c "composer install;composer dump-autoload;php reciever.php > logs.log"






