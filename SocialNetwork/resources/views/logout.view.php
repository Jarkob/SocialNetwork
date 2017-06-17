<?php

require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/login.php");
require_once(CLASSES_PATH ."/logout.php");
echo "<h1>Alles requiren ging</h1>";
if(login::isLoggedIn(session_id())) {
	$user = user::findUserBySid(session_id());
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