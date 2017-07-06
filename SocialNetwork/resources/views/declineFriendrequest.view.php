<?php
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/friendreques.php");

if(isset($_GET['id'])) {
	$username = user::findUserBySid(session_id());
	$user = new user($username);

	$friendrequest = new friendrequest($_GET['id']);
	if($friendrequest->getEmpfaenger() == $username) {
		$user->deleteFriendrequest($friendrequest);
		?>
		<p>Sie haben die Freundschaftsanfrage von <?= $friendrequest->getSender()?> abgelehnt.</p>
		<?php
	} else {
		?>
		<p>Sie können keine Freundschaftsanfragen aktzeptieren, die nicht an Sie gesendet wurden.</p>
		<?php
	}
} else {
	?>
	<p>Sie müssen die ID einer Freundschaftsanfrage übergeben, um sie abzulehnen.</p>
	<?php
}
?>