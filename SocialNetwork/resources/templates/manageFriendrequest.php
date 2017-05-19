<?php
$loggedIn = getLoginStatus($pdo, session_id());

if($loggedIn) {
	$username = getUserName($pdo, session_id());

	$sql = "SELECT * FROM friendrequest WHERE empfaenger_friendrequest = :username";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':username' => $username));
	?>
	<div>
		<h3>Erhaltene Freundschaftsanfragen</h3>
	<?php
	while($row = $statement->fetch()) {
		?>
		<p>Freundschaftsanfrage von <?= $row['sender_friendrequest']?></p>
		<p><a href="?page=acceptFriendrequest&id=<?= $row['id']?>">Bestätigen</a></p>
		<p><a href="?page=declineFriendrequest&id=<?= $row['id']?>">Ablehnen</a></p><br>
		<?php
	}
	?>
	</div>
	<?php
	$sql = "SELECT * FROM friendrequest WHERE sender_friendrequest = :username";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':username' => $username));
	?>
	<div>
		<h3>Gesendete Freundschaftsanfragen</h3>
	<?php
	while($row = $statement->fetch()) {
		?>
		<p>Freundschaftsanfrage an <?= $row['empfaenger_friendrequest']?></p>
		<?php
	}
	?>
	</div>
	<?php
} else {
	?>
	<p>Bitte loggen Sie sich zuerst ein.</p>
	<p><a href="?page=login">Zum Login</a></p>
	<?php
}
?>