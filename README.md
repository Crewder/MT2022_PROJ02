# pas-claude

# Installation 
docker-compose build  
docker-compose up -d  

#Configuration webserver

sudo docker exec -ti webserver /bin/bash  
composer install  

# logs / listen 

php recievelog.php > logs.log
