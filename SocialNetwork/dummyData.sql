INSERT INTO user (username, vorname, nachname, gebdatum, passwort)
VALUES 
	('admin_jakob',
	 'Jakob',
	 'Bussas',
	 '1997-07-27',
	 '$2y$10$u2rJVufc9BIVp/7RYXn8fujS5nRnoAlpvD0XPdEU6aMCbPYIa58aa'
	),

	('testfreund',
	 'Test',
	 'Freund',
	 '2000-12-12',
	 '$2y$10$u2rJVufc9BIVp/7RYXn8fujS5nRnoAlpvD0XPdEU6aMCbPYIa58aa'
	);
    
INSERT INTO friendship (freund1, freund2)
VALUES
	('admin_jakob',
     'testfreund'
	);

INSERT INTO entry (content, autor)
VALUES
	('<p>Hallo Welt</p><p>Dies ist der erste Eintrag und nur ein Testeintrag.</p><p>Ich hoffe es klappt.</p><br /><p>Vale</p>',
     'admin_jakob'
	),
    
    ('<p>Hallo</p><p>Dies ist der zweite Eintrag, diesmal verfasst vom Testuser.</p><br /><p>Vale</p>',
     'testfreund'
	),
    
    ('<p>Hallo</p><p>Noch ein eintrag von Jakobs Admin-User</p>',
	 'admin_jakob'
    );