CREATE DATABASE socialnetwork;

USE socialnetwork;

CREATE TABLE user(
	username VARCHAR(20) NOT NULL PRIMARY KEY,
	vorname VARCHAR(50),
	nachname VARCHAR(50),
	gebdatum DATE,
	passwort VARCHAR(100) NOT NULL,
	sid VARCHAR(100) DEFAULT "sessionid" NOT NULL,
    geschlecht VARCHAR(20),
    beziehungsstatus VARCHAR(20)
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


CREATE TABLE comment(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    parent_id INT NOT NULL,
    content TEXT NOT NULL,
    autor VARCHAR(20) NOT NULL,
    zeit TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT entry_id
    FOREIGN KEY (parent_id)
    REFERENCES socialnetwork.entry (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

    CONSTRAINT user_autor
    FOREIGN KEY (autor)
    REFERENCES socialnetwork.user (username)
    ON DELETE CASCADE
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
    sender_id VARCHAR(20) NOT NULL,
    empfaenger_id VARCHAR(20) NOT NULL,
	verlauf_id INT NOT NULL,
    
    INDEX verlauf_id (verlauf_id),
    CONSTRAINT verlauf_id
    FOREIGN KEY (verlauf_id)
    REFERENCES socialnetwork.verlauf (id)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
    
    INDEX sender_id (sender_id),
    CONSTRAINT sender_id
    FOREIGN KEY (sender_id)
    REFERENCES socialnetwork.user (username)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
    
    INDEX empfaenger_id (empfaenger_id),
    CONSTRAINT empfaenger_id
    FOREIGN KEY (empfaenger_id)
    REFERENCES socialnetwork.user (username)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
);


CREATE TABLE interesse(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    bezeichnung VARCHAR(30) NOT NULL,
    user_id VARCHAR(20) NOT NULL,
    
    INDEX user_id (user_id),
    CONSTRAINT user_id
    FOREIGN KEY (user_id)
    REFERENCES socialnetwork.user (username)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
);


CREATE TABLE ort(
	stadtname VARCHAR(20) NOT NULL PRIMARY KEY,
    plz INT NOT NULL
);

CREATE TABLE access_log(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    zeit TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    ip VARCHAR(40) NOT NULL,
    browser VARCHAR(150) NOT NULL,
    referrer VARCHAR(150) NOT NULL,
    query VARCHAR(150)
);


CREATE TABLE gefaelltMir(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    autor_user VARCHAR(20) NOT NULL,
    gefallender_entry INT NOT NULL,
    
    INDEX autor_user (autor_user),
    CONSTRAINT autor_user
    FOREIGN KEY (autor_user)
    REFERENCES socialnetwork.user (username)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
    
    INDEX gefallender_entry (gefallender_entry),
    CONSTRAINT gefallender_entry
    FOREIGN KEY (gefallender_entry)
    REFERENCES socialnetwork.entry (id)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
);


CREATE TABLE notification(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(20) NOT NULL,
    type VARCHAR(45) NOT NULL,
    typeid INT NOT NULL,
    seen VARCHAR(20) DEFAULT 'false',

    CONSTRAINT notification_user
    FOREIGN KEY (user)
    REFERENCES socialnetwork.user (username)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
);

