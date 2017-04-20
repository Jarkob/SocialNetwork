<div id="editProfile">

<?php

$loggedIn = getLoginStatus(session_id());

if($loggedIn) {
	$username = getUserName(session_id());

	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');
	

	if(isset($_POST['vorname'])
		|| isset($_POST['nachname'])
		|| isset($_POST['gebdatum'])
		|| isset($_POST['geschlecht'])
		|| isset($_POST['bezstatus'])
		) {
		$sql = "UPDATE user SET 
			vorname = :vorname,
			nachname = :nachname,
			gebdatum = :gebdatum,
			geschlecht = :geschlecht,
			bezstatus = :bezstatus 
			WHERE username = :username";
		$statement = $pdo->prepare($sql);
		$statement->execute(array(
			':vorname' => $_POST['vorname'],
			':nachname' => $_POST['nachname'],
			':gebdatum' => $_POST['gebdatum'],
			':geschlecht' => $_POST['geschlecht'],
			':bezstatus' => $_POST['bezstatus'],
			':username' => $username
		));

		//interessen ????

	}


	$sql = "SELECT * FROM user WHERE username = :username";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':username' => $username));

	while($row = $statement->fetch()) {
		$vorname = $row['vorname'];
		$nachname = $row['nachname'];
		$gebdatum = $row['gebdatum'];
		$geschlecht = $row['geschlecht'];
		$bezstatus = $row['bezstatus'];
	}

	//Interessen raussuchen
	$interessen = array();
	$sql = "SELECT * FROM interesse WHERE user_id = :username";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':username' => $username));

	while($row = $statement->fetch()) {
		$interessen[] = $row['bezeichnung'];
	}

	?>
	<form action="?page=editProfile" method="post">
		<p>
			<label for="vorname">Vorname</label>
			<input id="vorname" name="vorname" value="<?= $vorname?>">
		</p>
		<p>
		<label for="nachname">Nachname</label>
		<input id="nachname" name="nachname" value="<?= $nachname?>">
		</p>
		<p>
		<label for="gebdatum">Geburtsdatum</label>
		<input id="gebdatum" name="gebdatum" value="<?= $gebdatum?>">
		</p>
		<p>
		<label for="geschlecht">Geschlecht</label>
		<input id="geschlecht" name="geschlecht" value="<?= $geschlecht?>">
		</p>
		<p>
		<label for="bezstatus">Beziehungsstatus</label>
		<input id="bezstatus" name="bezstatus" value="<?= $bezstatus?>">
		</p>
		<?php
		for($i = 0; $i < count($interessen); $i++) {
			?>
			<input name="interesse<?= $i?>" value="<?= $interessen['$i']?>">
			<?php
		}
		?>

		<button type="submit">Speichern</button>
	</form>
	<?php

} else {
	?>
	<p>Bitte loggen Sie sich zuerst ein.</p>
	<p><a href="?page=login">Zum Login</a></p>
	<?php
}
?>

</div>