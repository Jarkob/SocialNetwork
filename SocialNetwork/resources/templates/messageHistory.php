<div class="verlauf">

<?php

$loggedIn = getLoginStatus(session_id());

if($loggedIn) {
	$username = getUserName(session_id());
	$id = $_GET['id'];

	require_once(TEMPLATES_PATH . "/newMessage.php");

	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

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