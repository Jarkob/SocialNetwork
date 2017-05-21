<div class="verlauf">

<?php

$loggedIn = getLoginStatus($pdo, session_id());

if($loggedIn) {
	$username = getUserName($pdo, session_id());

	require_once(TEMPLATES_PATH . "/newMessage.php");

	$id = $_GET['id'];

	//Nachrichten anzeigen
	$sql = "SELECT * FROM message WHERE verlauf_id = :id ORDER BY zeit DESC LIMIT 20";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':id' => $id));

	while($row = $statement->fetch()) {
		if($row['sender_id'] == $username) {
		?>
			<div class="rmessage">
		<?php
		} else {
		?>
			<div class="message">
		<?php
		}
		?>
			<p><i><?= $row['zeit']?></i></p>
			<p><b><?= $row['sender_id']?></b></p>
			<p><?= $row['content']?></p>
		</div>
		<?php
	}
} else {
	?>
	<p>Bitte loggen Sie sich zuerst ein.</p>
	<p><a href="?page=login">Zum Login</a></p>
	<?php
}


?>

</div>