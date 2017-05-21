<div id="editProfile">

<?php

$loggedIn = getLoginStatus($pdo, session_id());

if($loggedIn) {
	$username = getUserName($pdo, session_id());

	if(isset($_POST['vorname'])
		|| isset($_POST['nachname'])
		|| isset($_POST['gebdatum'])
		|| isset($_POST['geschlecht'])
		|| isset($_POST['bezstatus'])
		) {
		$sql = "UPDATE user SET 
			vorname = :vorname,
			nachname = :nachname,
			gebdatum = :gebdatum,
			geschlecht = :geschlecht,
			bezstatus = :bezstatus 
			WHERE username = :username";
		$statement = $pdo->prepare($sql);
		$statement->execute(array(
			':vorname' => $_POST['vorname'],
			':nachname' => $_POST['nachname'],
			':gebdatum' => $_POST['gebdatum'],
			':geschlecht' => $_POST['geschlecht'],
			':bezstatus' => $_POST['bezstatus'],
			':username' => $username
		));

		//interessen ????

	}

	if(isset($_FILES['datei']['name'])) {

		$upload_folder = 'img/content/profile/'; //Das Upload-Verzeichnis
		$filename = pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME);
		$extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));
		 
		 
		//Überprüfung der Dateiendung
		$allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
		if(!in_array($extension, $allowed_extensions)) {
		 	die("Ungültige Dateiendung. Nur png, jpg, jpeg und gif-Dateien sind erlaubt");
		}
		 
		//Überprüfung der Dateigröße
		$max_size = 500*1024; //500 KB
		if($_FILES['datei']['size'] > $max_size) {
		 	die("Bitte keine Dateien größer 500kb hochladen");
		}
		 
		//Überprüfung dass das Bild keine Fehler enthält
		if(function_exists('exif_imagetype')) { //Die exif_imagetype-Funktion erfordert die exif-Erweiterung auf dem Server
		 	$allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
		 	$detected_type = exif_imagetype($_FILES['datei']['tmp_name']);
		 	if(!in_array($detected_type, $allowed_types)) {
		 	die("Nur der Upload von Bilddateien ist gestattet");
		 	}
		}
		 
		//Pfad zum Upload
		$new_path = $upload_folder.$username.'.'.$extension;
		 
		//Neuer Dateiname falls die Datei bereits existiert
		if(file_exists($new_path)) { //Falls Datei existiert, lösche die alte Datei
		 	unlink($new_path);
		}
		 
		//Alles okay, verschiebe Datei an neuen Pfad
		move_uploaded_file($_FILES['datei']['tmp_name'], $new_path);
	}


	//Profil anzeigen
	$sql = "SELECT * FROM user WHERE username = :username";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':username' => $username));

	while($row = $statement->fetch()) {
		$vorname = $row['vorname'];
		$nachname = $row['nachname'];
		$gebdatum = $row['gebdatum'];
		$geschlecht = $row['geschlecht'];
		$bezstatus = $row['bezstatus'];
	}

	//Interessen raussuchen
	$interessen = array();
	$sql = "SELECT * FROM interesse WHERE user_id = :username";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':username' => $username));

	while($row = $statement->fetch()) {
		$interessen[] = $row['bezeichnung'];
	}

	?>
	<form action="?page=editProfile" method="post" enctype="multipart/form-data">
		
		<label for="profilbild">Profilbild</label>
		<input id="profilbild" type="file" name="datei">

		<p>
			<label for="vorname">Vorname</label>
			<input id="vorname" name="vorname" value="<?= $vorname?>">
		</p>
		<p>
		<label for="nachname">Nachname</label>
		<input id="nachname" name="nachname" value="<?= $nachname?>">
		</p>
		<p>
		<label for="gebdatum">Geburtsdatum</label>
		<input id="gebdatum" name="gebdatum" value="<?= $gebdatum?>">
		</p>
		<p>
		<label for="geschlecht">Geschlecht</label>
		<input id="geschlecht" name="geschlecht" value="<?= $geschlecht?>">
		</p>
		<p>
		<label for="bezstatus">Beziehungsstatus</label>
		<input id="bezstatus" name="bezstatus" value="<?= $bezstatus?>">
		</p>
		<?php
		for($i = 0; $i < count($interessen); $i++) {
			?>
			<input name="interesse<?= $i?>" value="<?= $interessen['$i']?>">
			<?php
		}
		?>

		<button type="submit">Speichern</button>
	</form>
	<?php

} else {
	?>
	<p>Bitte loggen Sie sich zuerst ein.</p>
	<p><a href="?page=login">Zum Login</a></p>
	<?php
}
?>

</div>