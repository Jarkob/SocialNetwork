<?php
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/history.php");

$username = user::findUserBySid(session_id());

$friend = $_GET['friend'];

$result = history::findHistoryByParticipating($username, $friend);
if($result != "" && $result != null) {
	$sql = "INSERT INTO verlauf (teilnehmer1, teilnehmer2, zeit) VALUES (:username, :friend, CURRENT_TIMESTAMP)";
	$params = array(":username" => $username, ":friend" => $friend);
	sql::exe($sql, $params);

	?>
	<p>
		Sie können <?= $friend?> jetzt Nachrichten senden.
	</p>
	<script type="text/javascript">
		document.location.href = "index.php?page=histories";
	</script>

	<?php
} else {
	?>
	<script type="text/javascript">
		document.location.href = "index.php?page=chat&id=<?= $result?>";
	</script>
	<?php
}
?>