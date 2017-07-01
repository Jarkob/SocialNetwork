<?php

require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/login.php");
require_once(CLASSES_PATH ."/logout.php");
// Bis hierhin ok
echo "Hilfe ich werde ausgeloggt";
if(login::isLoggedIn(session_id())) {
	$username = user::findUserBySid(session_id());
	// Achtung: Diese Funktion gibt kein userobjekt zurück sondern nur den usernamen
	$user = new user($username);
	logout::logoutUser($user);
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