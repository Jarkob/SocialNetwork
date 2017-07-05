<?php
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/friendrequest.php");

if(isset($_GET['id'])) {
	$username = user::findUserBySid(session_id());
	$user = new user($username);
	if(!friendrequest::checkFriendrequest($username, $_GET['id'])) {
		$user->sendFriendrequest($_GET['id']);
		?>
		<p>Sie haben <?= $_GET['id']?> eine Freundschaftsanfrage gesendet.</p>
		<?php
	} else {
		?>
		<p>Sie haben <?= $_GET['id']?> bereits eine Freundschaftsanfrage gesendet.</p>
		<?php
	}
}

?>