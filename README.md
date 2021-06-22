# MT2022_PROJ02
*Camille Arsac, Rémi Coufourier, Florian Leroy et Steven Nativel*

### Table de contenu

* [1. Description](#description)
* [2. Lancer le projet](#lancer-projet)
  * [1. Installation](#installation)
  * [2. Configuration webserver](#configuration-webserver)
  * [3. Configuration listenserver](#configuration-listenserver)
* [3. Tester le projet](#tester-projet)
* [4. Screens](#screens)


## 1 - Description du projet
<a name="description"/>
Une petite application permettant de redimensionner une image et mettant en relation rabbitMQ.

## 2 - Lancer le projet
<a name="lancer-projet"/>

Lancer le script install.sh  
Si une erreur se produit voici la marche à suivre :

###### 1 - Installation du projet
<a name="installation"/>

sudo docker-compose build  
sudo docker-compose up -d  

###### 2 - Configuration du webserver
<a name="configuration-webserver"/>

sudo docker exec -ti webserver /bin/bash  
sudo composer install  
sudo composer dump-autoload

###### 3 - Configuration du listenserver
<a name="configuration-listenserver"/>

sudo docker exec -ti listenserver /bin/bash  
sudo composer install  
sudo composer dump-autoload   
php reciever.php > logs.log


## 3 - Tester le projet
<a name="tester-projet"/>

Aller sur http://localhost:8080 et uploader une image    
Pour aller sur rabbitMQ : http://localhost:15672 (identifiant : guest, mot de passe : guest)  
Pour aller sur phpMyAdmin : http://localhost:9999 (identifaint : root, mot de passe : root)

## 4 - Screens
<a name="screens"/>

![image](https://user-images.githubusercontent.com/78849895/122954456-be2f2480-d37f-11eb-8dbb-568a8c258acf.png)

![image](https://user-images.githubusercontent.com/78849895/122954519-cb4c1380-d37f-11eb-9552-61bc7e15a8be.png)

![image](https://user-images.githubusercontent.com/78849895/122954554-d2732180-d37f-11eb-87cb-1463af98ab50.png)

