DROP DATABASE IF EXISTS forum;
CREATE DATABASE forum;

USE forum;

CREATE TABLE avatar(
   id INT(10) AUTO_INCREMENT,
   picture VARCHAR(255) NOT NULL,
   PRIMARY KEY(id)
);

CREATE TABLE user(
     id INT PRIMARY KEY AUTO_INCREMENT,
     name VARCHAR(255) NOT NULL,
     email VARCHAR(255) NOT NULL,
     password VARCHAR(255) NOT NULL,
     avatar_id INT NOT NULL,
     CONSTRAINT `fk_user_avatar` FOREIGN KEY (avatar_id) REFERENCES avatar(id)
);