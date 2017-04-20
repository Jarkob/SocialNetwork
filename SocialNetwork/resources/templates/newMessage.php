<div class="newMessage">
	<h4>Neue Nachricht</h4>
	<form action="index.php?page=messageHistory&id=<?= $_GET['id']?>" method="post">
		<input type="hidden" name="id" value="<?= $_GET["id"]?>">
		<textarea name="content"></textarea>
		<button type="submit">Senden</button>
	</form>
</div>

<?php

if(array_key_exists('content', $_POST)) {

	$username = getUserName(session_id());
	$id = $_GET['id'];
	
	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	//empfaenger herausfinden
	$sql = "SELECT * FROM verlauf WHERE id = :id";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':id' => $id));

	while($row = $statement->fetch()) {
		if($row['teilnehmer1'] == $username) {
			$empfaenger = $row['teilnehmer2'];
		} else if($row['teilnehmer2'] == $username) {
			$empfaenger = $row['teilnehmer1'];
		}
	}

	$sql = "INSERT INTO message (content, sender_id, empfaenger_id, verlauf_id) VALUES (:content, :sender, :empfaenger, :verlauf)";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':content' => $_POST['content'], ':sender' => $username, ':empfaenger' => $empfaenger, ':verlauf' => $id));

	//setzt das letzte Änderungsdatum vom Verlauf auf den CURRENT TIMESTAMP
	$sql = "UPDATE verlauf SET zeit = CURRENT_TIMESTAMP WHERE id = :id";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':id' => $id));
}

?>