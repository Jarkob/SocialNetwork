<?php
$loggedIn = getLoginStatus($pdo, session_id());

if($loggedIn) {
	$username = getUserName($pdo, session_id());
	$id = $_GET['id'];

	//Friendrequest löschen
	$sql = "DELETE FROM friendrequest WHERE id = :id";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':id' => $id));

	?>
	<p>Du hast die Freundschaftsanfrage abgelehnt.</p>
	<?php

} else {
	?>
	<p>Bitte loggen Sie sich zuerst ein.</p>
	<p><a href="?page=login">Zum Login</a></p>
	<?php
}
?>