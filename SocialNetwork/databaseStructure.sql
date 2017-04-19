CREATE DATABASE socialnetwork;

USE socialnetwork;

CREATE TABLE user(
	username VARCHAR(20) NOT NULL PRIMARY KEY,
	vorname VARCHAR(50) NOT NULL,
	nachname VARCHAR(50) NOT NULL,
	gebdatum DATE NOT NULL,
	passwort VARCHAR(100) NOT NULL,
	sid VARCHAR(100) DEFAULT "sessionid" NOT NULL
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
	zeit TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

	INDEX user_autor (autor),
	CONSTRAINT user_autor
	FOREIGN KEY (autor)
	REFERENCES socialnetwork.user (username)
	ON DELETE NO ACTION
	ON UPDATE CASCADE
);


CREATE TABLE friendrequest(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	zeit TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	sender_friendrequest VARCHAR(20) NOT NULL,
	empfaenger_friendrequest VARCHAR(20) NOT NULL,

	INDEX user_sender_friendrequest (sender_friendrequest),
	CONSTRAINT user_sender_friendrequest
	FOREIGN KEY (sender_friendrequest)
	REFERENCES socialnetwork.user (username)
	ON DELETE NO ACTION
	ON UPDATE CASCADE,

	INDEX user_empfaenger (empfaenger_friendrequest),
	CONSTRAINT user_empfaenger_friendrequest
	FOREIGN KEY (empfaenger_friendrequest)
	REFERENCES socialnetwork.user (username)
	ON DELETE NO ACTION
	ON UPDATE CASCADE
);


CREATE TABLE verlauf(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	zeit TIMESTAMP NOT NULL,/*soll der Zeitpunkt der letzten Änderung sein, muss also bei jeder neuen Nachricht geändert werden.*/
	teilnehmer1 VARCHAR(20) NOT NULL,
	teilnehmer2 VARCHAR(20) NOT NULL,
    
    INDEX user_verlauf_teilnehmer1 (teilnehmer1),
    CONSTRAINT user_verlauf_teilnehmer1
    FOREIGN KEY (teilnehmer1)
    REFERENCES socialnetwork.user (username)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
    
    INDEX user_verlauf_teilnehmer2 (teilnehmer2),
    CONSTRAINT user_verlauf_teilnehmer2
    FOREIGN KEY (teilnehmer2)
    REFERENCES socialnetwork.user (username)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
);


CREATE TABLE message(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	zeit TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	content TEXT NOT NULL,
	sender_message VARCHAR(20) NOT NULL,
	verlauf_id INT NOT NULL,
    
    INDEX verlauf_id (verlauf_id),
    CONSTRAINT verlauf_id
    FOREIGN KEY (verlauf_id)
    REFERENCES socialnetwork.verlauf (id)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
	
);
