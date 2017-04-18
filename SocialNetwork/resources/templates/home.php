<div id="home">

<?php
$loggedin = getLoginStatus(session_id());

if($loggedin) {
	$username = getUserName(session_id());

	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');


	//neuer Eintrag
	require_once(TEMPLATES_PATH . "/newEntry.php");


	$friends = getFriends($username);

	//die Abfrage soll die Einträge von allen Freunden abfragen, diese aufsteigend nach der Zeit
	//ordnen, und dann auf 10 begrenzen
	//edit: auch eigene Einträge sollen angezeigt werden
	$sql = "SELECT * FROM entry WHERE autor = '". $username;
	foreach($friends as $friend) {
		$sql .= "' OR autor = '". $friend;
	}
	$sql .= "' ORDER BY zeit ASC LIMIT 10";

	$statement = $pdo->prepare($sql);
	$statement->execute();
	while($row = $statement->fetch()) {
		renderEntry($row['id']);
	}

} else {
	?>
	<p>Bitte loggen Sie sich zuerst ein.</p>
	<p><a href="?page=login">Zum Login</a></p>
	<?php
}
?>
</div>