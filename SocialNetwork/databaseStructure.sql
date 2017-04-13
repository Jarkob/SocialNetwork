CREATE DATABASE socialnetwork,

USE socialnetwork;

CREATE TABLE user(
	username VARCHAR(20) NOT NULL PRIMARY KEY,
	vorname VARCHAR(50) NOT NULL,
	nachname VARCHAR(50) NOT NULL,
	gebdatum DATE NOT NULL
);


CREATE TABLE friendship(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	beginn TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	freund1 VARCHAR(20) NOT NULL,
	freund2 VARCHAR(20) NOT NULL,

	INDEX user_freund1 (freund1),
	CONSTRAINT user_freund1
	FOREIGN KEY (freund1)
	REFERENCES socialnetwork.user (username)
	ON DELETE NO ACTION
	ON UPDATE CASCADE,

	INDEX user_freund2 (freund2),
	CONSTRAINT user_freund2
	FOREIGN KEY (freund2)
	REFERENCES socialnetwork.user (username)
	ON DELETE NO ACTION
	ON UPDATE CASCADE
);


CREATE TABLE entry(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	content TEXT NOT NULL,
	autor VARCHAR(20) NOT NULL,

	INDEX user_autor (autor),
	CONSTRAINT user_autor
	FOREIGN KEY (autor)
	REFERENCES socialnetwork.user (username)
	ON DELETE NO ACTION
	ON UPDATE CASCADE
);

#Tabellen anlegen klappt, Funktionalität muss noch getestet werden