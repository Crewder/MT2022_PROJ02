# MT2022 PROJ02

# Installation 
docker-compose build  
docker-compose up -d  

#Configuration webserver

sudo docker exec -ti webserver /bin/bash  
composer install  
composer dump-autoload  

# logs / listen 

php recievelog.php > logs.log
