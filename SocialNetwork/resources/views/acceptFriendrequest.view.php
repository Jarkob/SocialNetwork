<?php
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/friendrequest.php");

if(isset($_GET['id'])) {
	$username = user::findUserBySid(session_id());
	$user = new user($username);

	$friendrequest = new friendrequest($_GET['id']);
	if($friendrequest->getEmpfaenger() == $username) {
		$user->acceptFriendrequest($friendrequest);
		?>
		<p>Sie sind jetzt mit <?= $friendrequest->getSender()?> befreundet.</p>
		<?php
	} else {
		?>
		<p>Sie können keine Freundschaftsanfragen aktzeptieren, die nicht an Sie gesendet wurden.</p>
		<?php
	}
} else {
	?>
	<p>Sie müssen die ID einer Freundschaftsanfrage übergeben, um sie zu akzeptieren.</p>
	<?php
}

?>