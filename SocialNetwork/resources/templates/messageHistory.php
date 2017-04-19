<div class="verlauf">

<?php

$loggedIn = getLoginStatus(session_id());

if($loggedIn) {
	$username = getUserName(session_id());
	$id = $_GET['id'];

	require_once(TEMPLATES_PATH . "/newMessage.php");

	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$sql = "SELECT * FROM messages WHERE verlauf_id = :id ORDER BY zeit DESC LIMIT 20";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':id' => $id));
echo $id . "huhu welt <br>";
	while($row = $statement->fetch()) {
		?>
		<div class="message">
			<h4><?= $row['sender_id']?></h4>
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