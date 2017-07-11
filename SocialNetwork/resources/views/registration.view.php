<?php

// Wenn die Parameter gesetzt sind wird der Registrierungsprozess gestartet, ansonsten wird das Formular angezeigt
if(isset($_POST['newUserName'], $_POST['newPassword'], $_POST['repeatNewPassword'])) {
	// Validitätsprüfung
	$username = $_POST['newUserName'];

} else {
?>
<form action="?page=registration" method="post">
	<p>
		<label for="newUserName">Benutzername</label>
		<input id="newUserName" name="newUserName">
	</p>
	<p>
		<label for="newPassword">Passwort</label>
		<input id="newPassword" name="newPassword">
	</p>
	<p>
		<label for="repeatNewPassword">Passwort wiederholen</label>
		<input id="repeatNewPassword" name="repeatNewPassword">
	</p>
	<p>
		<button type="submit">Registrieren</button>
	</p>
</form>
<?php
}
?>