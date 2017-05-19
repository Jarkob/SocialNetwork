<div id="newEntry">
<h3>Neuer Post</h3>
<form action="index.php" method="post">
	<textarea name="content"></textarea>
	<button type="submit">Posten</button>
</form>

</div>

<?php

if(array_key_exists('content', $_POST)) {

	$username = getUserName($pdo, session_id());

	$sql = "INSERT INTO entry (content, autor) VALUES (:content, :autor)";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':content' => $_POST['content'], ':autor' => $username));

	while($row = $statement->fetch()) {
		echo "neuer Eintrag wurde erstellt";
	}
	
}

?>