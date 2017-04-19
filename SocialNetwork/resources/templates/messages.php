<div id="messages">
<h2>Nachrichten</h2>
<?php
//fucked up
//zeige alle Konversationen an, an denen die Person die die Seite aufruft beteiligt ist

$loggedin = getLoginStatus(session_id());
if($loggedin) {
	$username = getUserName(session_id());

	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$sql = "SELECT * FROM verlauf WHERE teilnehmer1 = :username OR teilnehmer2 = :username ORDER BY zeit DESC LIMIT 10";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':username' => $username));

	while($row = $statement->fetch()) {
		?>
		<div class="verlauf">
		<p><a href="?page=messageHistory&id=<?= $row['id']?>">Konversation mit 
			<?php
			if($row['teilnehmer1'] == $username) {
				echo $row['teilnehmer2'];
			} else if($row['teilnehmer2'] == $username) {
				echo $row['teilnehmer1'];
			}
			//Zeigt den Namen des anderen Teilnehmers an der Konversation an, hier muss noch ein Link dahin eingeführt werden
			?>
		</a></p>

		</div>
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