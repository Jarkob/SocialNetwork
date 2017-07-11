<?php
require_once(CLASSES_PATH ."/sql.php");
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/profile.php");

$username = user::findUserBySid(session_id());
$user = new user($username);

$profile = new profile($user);

if(isset($_POST['vorname'], $_POST['nachname'], $_POST['gebdatum'], $_POST['geschlecht'], $_POST['bezstatus'])) {
	$sql = "UPDATE user SET vorname = :vorname, 
		nachname = :nachname, 
		gebdatum = :gebdatum, 
		geschlecht = :geschlecht, 
		bezstatus = :bezstatus 
		WHERE username = :username";
	$params = array(":vorname" => $_POST['vorname'],
		":nachname" => $_POST['nachname'],
		":gebdatum" => $_POST['gebdatum'],
		":geschlecht" => $_POST['geschlecht'],
		":bezstatus" => $_POST['bezstatus'],
		":username" => $username
		);
	printf($params);
	echo "vor sql";
	sql::exe($sql, $params);
	echo "nach sql";
}

?>
<form action="?page=editProfile" method="post">
	<p>
		<label for="vorname">Vorname</label>
		<input id="vorname" name="vorname" value="<?= $profile->getUser()->getVorname()?>">
	</p>
	<p>
		<label for="nachname">Nachname</label>
		<input id="nachname" name="nachname" value="<?= $user->getNachname()?>">
	</p>
	<p>
		<label for="gebdatum">Geburtsdatum</label>
		<input id="gebdatum" name="gebdatum" value="<?= $user->getGebdatum()?>">
	</p>
	<p>
		<label for="geschlecht">Geschlecht</label>
		<input id="geschlecht" name="geschlecht" value="<?= $user->getGeschlecht()?>">
	</p>
	<p>
		<label for="bezstatus">Beziehungsstatus</label>
		<input id="bezstatus" name="bezstatus" value="<?= $user->getBezstatus()?>">
	</p>
	<button type="submit">Speichern</button>
</form>