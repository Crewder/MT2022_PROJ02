#!/bin/bash

printf "Installation du projet\n"
docker-compose build
docker-compose up -d

servers=(webserver listenserver)

for server in "${servers[@]}"
do
    echo "Configuration du ${server}"
    docker exec -ti "${server}" /bin/bash -c "composer install;composer dump-autoload"
done

php recievelog.php > logs.log







