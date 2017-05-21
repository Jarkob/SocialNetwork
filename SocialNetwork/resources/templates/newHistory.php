<?php

$loggedIn = getLoginStatus($pdo, session_id());

if($loggedIn) {
	$username = getUserName($pdo, session_id());

	$freund = $_GET['freund'];

	$historyExists = false;
	$sql = "SELECT * FROM verlauf WHERE (teilnehmer1 = :username AND teilnehmer2 = :freund) OR (teilnehmer1 = :freund AND teilnehmer2 = :username)";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':username' => $username, ':freund' => $freund));
	while($row = $statement->fetch()) {
		$historyExists = true;
	}

	if(!$historyExists) {
		$sql = "INSERT INTO verlauf (teilnehmer1, teilnehmer2) VALUES (:teilnehmer1, :teilnehmer2)";
		$statement = $pdo->prepare($sql);
		$statement->execute(array(':teilnehmer1' => $username, ':teilnehmer2' => $freund));
	}

	$sql = "SELECT * FROM verlauf WHERE (teilnehmer1 = :username AND teilnehmer2 = :freund) OR (teilnehmer1 = :freund AND teilnehmer2 = :username)";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':username' => $username, ':freund' =>  $freund));
	while($row = $statement->fetch()) {
		$_GET['id'] = $row['id'];
	}
	
	require_once(TEMPLATES_PATH . "/newMessage.php");
}

?>