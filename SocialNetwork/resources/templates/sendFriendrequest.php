<?php
$loggedIn = getLoginStatus($pdo, session_id());

if($loggedIn) {
	$username = getUserName($pdo, session_id());

	$sql = "INSERT INTO friendrequest (sender_friendrequest, empfaenger_friendrequest)
	 VALUES (:sender, :empfaenger)";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':sender' => $username, ':empfaenger' => $_GET['id']));
	?>
	<p>Du hast <?= $_GET['id']?> eine Freundschaftsanfrage gesendet.</p>
	<?php

} else {
	?>
	<p>Bitte loggen Sie sich zuerst ein.</p>
	<p><a href="?page=login">Zum Login</a></p>
	<?php
}