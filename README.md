# MT2022_PROJ02
*Camille Arsac, Rémi Coufourier, Florian Leroy et Steven Nativel*

### Table de contenu

* [1. Description](#description)
* [2. Installation](#installation)
* [3. Configuration webserver](#configuration-webserver)
* [4. Configuration listenserver](#configuration-listenserver)
* [5. Logs / Listen](#logs-listen)


## 1 - Description du projet
<a name="description"/>
Une petite application permettant de redimensionner une image et mettant en relation rabbitMQ.

## 2 - Installation du projet
<a name="installation"/>

sudo docker-compose build  
sudo docker-compose up -d  

## 3 - Configuration du webserver
<a name="configuration-webserver"/>

sudo docker exec -ti webserver /bin/bash  
sudo composer install  
sudo composer dump-autoload

## 4 - Configuration du listenserver
<a name="configuration-listenserver"/>

sudo docker exec -ti listenserver /bin/bash  
sudo composer install  
sudo composer dump-autoload


## 5 - Logs / Listen
<a name="logs-listen"/>

php recievelog.php > logs.log
