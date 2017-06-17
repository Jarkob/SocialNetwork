<?php

require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/login.php");
require_once(CLASSES_PATH ."/logout.php");
// Bis hierhin ok
if(login::isLoggedIn(session_id())) {
	$userDaten = user::findUserBySid(session_id());
	// Achtung: Diese Funktion gibt kein userobjekt zurück sondern nur ein array
	$user = new user($userDaten[0]['username']);
	$user->changeSid('loggedOut');
?>
	<p>Sie wurden ausgeloggt.</p>
	<script type="text/javascript">
		document.location.href = "index2.php";
	</script>
<?php
} else {
?>
	<p>Sie sind nicht eingeloggt.</p>
<?php
}
?>