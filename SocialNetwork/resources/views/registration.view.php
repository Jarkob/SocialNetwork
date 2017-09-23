<?php

// Wenn die Parameter gesetzt sind wird der Registrierungsprozess gestartet, ansonsten wird das Formular angezeigt
if(isset($_POST['newUserName'], $_POST['newPassword'], $_POST['repeatNewPassword'])) {
	require_once(CLASSES_PATH ."/sql.php");
	require_once(CLASSES_PATH ."/user.php");

	// Validitätsprüfung
	$username = $_POST['newUserName'];
	$user = user::findUserByUserName($username);
	if(sizeof($user) != 0) {
		?>
		<p>Der gewünschte Benutzername ist bereits vergeben.</p>
		<?php
	} else {
		if($_POST['newPassword'] != $_POST['repeatNewPassword']) {
			?>
			<p>Die beiden Passwörter müssen übereinstimmen.</p>
			<?php
		} else {
			$password = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
			$sql = "INSERT INTO user (username, passwort) VALUES (:username, :passwort)";
			$params = array(":username" => $username, ":passwort" => $password);
			sql::exe($sql, $params);
			?>
			<p>Sie haben sich erfolgreich registriert.</p>
			<p>
				<a href="?page=login">Zum Login</a>
			</p>
			<?php
		}
	}
} else {
?>
<form action="?page=registration" method="post">
	<div class="form-group">
		<label for="newUserName">Benutzername</label>
		<input id="newUserName" class="form-control" name="newUserName">
	</div>
	<div class="form-group">
		<label for="newPassword">Passwort</label>
		<input id="newPassword" class="form-control" type="password" name="newPassword">
	</div>
	<div class="form-group">
		<label for="repeatNewPassword">Passwort wiederholen</label>
		<input id="repeatNewPassword" class="form-control" type="password" name="repeatNewPassword">
	</div>
	<button type="submit" class="btn btn-default">Registrieren</button>
</form>
<?php
}
?>