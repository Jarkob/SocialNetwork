<?php
require_once(CLASSES_PATH ."/user.php");
$username = user::findUserBySid(session_id());

$friend = $_GET['friend'];

$sql = "INSERT INTO verlauf (teilnehmer1, teilnehmer2) VALUES (:username, :friend)";
$params = array(":username" => $username, ":friend" => $friend);
sql::exe($sql, $params);

?>
<p>
	Sie können <?= $friend?> jetzt Nachrichten senden.
</p>
<script type="text/javascript">
	document.location.href = "index.php?page=histories";
</script>