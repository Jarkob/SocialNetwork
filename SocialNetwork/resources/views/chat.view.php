<?php
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/history.php");
require_once(CLASSES_PATH ."/message.php");

$username = user::findUserBySid(session_id());
$user = new user($username);

$history = new history($_GET['id']);

// Wenn eine neue Nachricht gesendet wurde, muss sie erstellt werden
if(isset($_POST['message'])) {
	message::createNewMessage($_POST['message'], $username, $history->getOtherParticipant($username), $history->getId());
}
echo "vor messages ini";
$messages = $history->getMessages();
echo "vor messages renderung";
foreach($messages as $message) {
	$message->renderMessage();
}

// Es wäre cool, wenn es ein Fenster geben würde, in dem man hochscrollen könnte, um alle Nachichten zu sehen.
// Hier muss noch die Möglichkeit geboten werden, eine neue Nachricht zu erstellen

?>
<form action="?page=chat&id=<?= $_GET['id']?>" method="post">
	<input name="message">
	<button type="submit">Senden</button>
</form>