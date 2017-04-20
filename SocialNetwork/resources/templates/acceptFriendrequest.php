<?php
$loggedIn = getLoginStatus(session_id());

if($loggedIn) {
	$username = getUserName(session_id());
	$id = $_GET['id'];

	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	//zugehörige Freunde raussuchen
	$sql = "SELECT * FROM friendrequest WHERE id = :id";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':id' => $id));
	while($row = $statement->fetch()) {
		$sender = $row['sender_friendrequest'];
		$empfaenger = $row['empfaenger_friendrequest'];
	}

	//Freundschaft erstellen
	$sql = "INSERT INTO friendship (freund1, freund2) VALUES (:freund1, :freund2)";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':freund1' => $sender, ':freund2' => $empfaenger));

	//Friendrequest löschen
	$sql = "DELETE FROM friendrequest WHERE id = :id";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':id' => $id));

	?>
	<p>Du bist jetzt mit <?= $sender?> befreundet.</p>
	<?php

} else {
	?>
	<p>Bitte loggen Sie sich zuerst ein.</p>
	<p><a href="?page=login">Zum Login</a></p>
	<?php
}
?>