<?php

if(isset($_POST['newUserName'], $_POST['newPassword'],
	$_POST['newVorname'], $_POST['newNachname'],
	$_POST['newGebdat'])) {
	$username = $_POST['newUserName'];

	$sql = "SELECT * FROM user WHERE username = :username";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':username' => $username));

	$usernameExistsAlready = false;
	while($row = $statement->fetch()) {
		$usernameExistsAlready = true;
	}

	if($usernameExistsAlready) {
		?>
		<p>Der Username existiert bereits, bitte wählen Sie einen anderen</p>
		<?php
	} else {
		$password = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
		$sql = "INSERT INTO user (username, vorname, nachname, gebdatum, passwort)
		 VALUES (:username, :vorname, :nachname, :gebdatum, :passwort)";
		$statement = $pdo->prepare($sql);
		$statement->execute(array(':username' => $username,
		 ':vorname' => $_POST['newVorname'],
		  ':nachname' => $_POST['newNachname'],
		   ':gebdatum' => $_POST['newGebdat'],
		    ':passwort' => $password));

		?>
		<p>Herzlichen Glückwunsch, Sie haben sich erfolgreich registriert.</p>
		<p><a href="?page=login">Zum Login</a></p>
		<?php
	}
} else {
?>
<form action="?page=registration" method="post">
	<p>
		<label for="newUserName">Benutzername</label>
		<input id="newUserName" name="newUserName">
	</p>
	<p>
		<label for="newPassword">Passwort</label>
		<input id="newPassword" type="password" name="newPassword">
	</p>
	<p>
		<label for="newVorname">Vorname</label>
		<input id="newVorname" name="newVorname">
	</p>
	<p>
		<label for="newNachname">Nachname</label>
		<input id="newNachname" name="newNachname">
	</p>
	<p>
		<label for="newGebdat">Geburtsdatum(YYYY-MM-DD)</label>
		<input id="newGebdat" name="newGebdat">
	</p>
	<p>
	<button type="submit">Registrieren</button>
	</p>
</form>
<?php
}
?>