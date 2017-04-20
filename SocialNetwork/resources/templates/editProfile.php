<div id="editProfile">

<?php

$loggedIn = getLoginStatus(session_id());

if($loggedIn) {
	$username = getUserName(session_id());

	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');
	
	$sql = "SELECT * FROM user WHERE username = :username";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':username' => $username));

	while($row = $statement->fetch()) {
		//Formular zum Editieren der Userdaten, dafür sollte erst eine finale Version von der User Entität erstellt werden
		?>
		<form>

		</form>
		<?php
	}


} else {
	?>
	<p>Bitte loggen Sie sich zuerst ein.</p>
	<p><a href="?page=login">Zum Login</a></p>
	<?php
}
?>

</div>