<?php
require_once(CLASSES_PATH ."/sql.php");
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/profile.php");

$username = user::findUserBySid(session_id());

if(isset($_POST['vorname'], $_POST['nachname'], $_POST['gebdatum'], $_POST['geschlecht'], $_POST['bezstatus'])) {
	$sql = "UPDATE user SET 
		vorname = :vorname, 
		nachname = :nachname, 
		gebdatum = :gebdatum, 
		geschlecht = :geschlecht, 
		bezstatus = :bezstatus 
		WHERE username = :username";
	$params = array(
		":vorname" => $_POST['vorname'],
		":nachname" => $_POST['nachname'],
		":gebdatum" => $_POST['gebdatum'],
		":geschlecht" => $_POST['geschlecht'],
		":bezstatus" => $_POST['bezstatus'],
		":username" => $username
		);
	
	sql::exe($sql, $params);
}


// Bildbehandlung
if(isset($_FILES['picture']['name'])) {
	if($_FILES['picture']['name'] != "") {
		$upload_folder = 'img/content/profile/'; //Das Upload-Verzeichnis
		if(!file_exists($upload_folder)) {
			mkdir($upload_folder);
		}

		echo "<script>alert('Hallo Welt');</script>";

		$filename = pathinfo($_FILES['picture']['name'], PATHINFO_FILENAME); // Diese Zeile ist glaube ich unnötig
		$extension = strtolower(pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION));
		 
		 
		//Überprüfung der Dateiendung
		$allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
		if(!in_array($extension, $allowed_extensions)) {
		 	die("Ungültige Dateiendung. Nur png, jpg, jpeg und gif-Dateien sind erlaubt");
		}
		 
		//Überprüfung der Dateigröße
		$max_size = 500*1024; //500 KB
		if($_FILES['picture']['size'] > $max_size) {
		 	die("Bitte keine Dateien größer 500kb hochladen");
		}
		 
		//Überprüfung dass das Bild keine Fehler enthält
		if(function_exists('exif_imagetype')) { //Die exif_imagetype-Funktion erfordert die exif-Erweiterung auf dem Server
		 	$allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
		 	$detected_type = exif_imagetype($_FILES['picture']['tmp_name']);
		 	if(!in_array($detected_type, $allowed_types)) {
		 		die("Nur der Upload von Bilddateien ist gestattet");
		 	}
		}
		 

		// Wenn der User vorher ein Profilbild hatte, muss dieses gelöscht werden.
		foreach($allowed_extensions as $allowed_extension) {
			if(file_exists($upload_folder . $username .".". $allowed_extension)) {
				unlink($upload_folder . $username .".". $allowed_extension);
			}
		}


		//Pfad zum Upload
		$new_path = $upload_folder.$username.'.'.$extension;
		 
		//Alles okay, verschiebe Datei an neuen Pfad
		move_uploaded_file($_FILES['picture']['tmp_name'], $new_path);
	}
}

$user = new user($username);
$profile = new profile($user);

?>
<form action="?page=editProfile" method="post" enctype="multipart/form-data">
	<div class="form-group">
		<label for="vorname">Vorname</label>
		<input id="vorname" class="form-control" classname="vorname" value="<?= $profile->getUser()->getVorname()?>">
	</div>
	<div class="form-group">
		<label for="nachname">Nachname</label>
		<input id="nachname" class="form-control" name="nachname" value="<?= $user->getNachname()?>">
	</div>
	<div class="form-group">
		<label for="gebdatum">Geburtsdatum</label>
		<input id="gebdatum" class="form-control" name="gebdatum" value="<?= $user->getGebdatum()?>">
	</div>
	<div class="form-group">
		<label for="geschlecht">Geschlecht</label>
		<input id="geschlecht" class="form-control" name="geschlecht" value="<?= $user->getGeschlecht()?>">
	</div>
	<div class="form-group">
		<label for="bezstatus">Beziehungsstatus</label>
		<input id="bezstatus" class="form-control" name="bezstatus" value="<?= $user->getBezstatus()?>">
	</div>

	<div class="form-group">
		<label for="newProfilePicture">Profilbild ändern</label>
		<div>
		<?php
		// Profilbild laden
		if(file_exists("img/content/profile/". $username .".jpg")) {
			?>
			<img id="profilePicture" title="Profilbild" src="img/content/profile/<?= $username?>.jpg" style="width: 300px">
			<?php
		} else if(file_exists("img/content/profile/". $username .".png")) {
			?>
			<img id="profilePicture" title="Profilbild" src="img/content/profile/<?= $username?>.jpg" style="width: 300px">
			<?php
		} else {
			?>
			<img id="profilePicture" title="Profilbild" src="img/content/profile/default.png" style="width: 300px">
			<?php
		}
		?>
		</div>
		<input id="newProfilePicture" type="file" name="picture">
	</div>

	<button type="submit" class="btn btn-default">Speichern</button>
</form>