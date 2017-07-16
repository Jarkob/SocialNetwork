<h2>Nachrichten</h2>
<?php

require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/history.php");

$username = user::findUserBySid(session_id());
$user = new user($username);
$histories = $user->getHistories();
// Fazit: Nachrichten auf diese Weise zu machen ist schlecht. Egal...
if(sizeof($histories) != 0) {
?>
<ul>
	<?php
	foreach($histories as $history) {
		?>
		<li>
			<a href="?chat&id=<?= $history['id']?>">
				Konversation mit 
				<?php
				$teilnehmer = $histories->getTeilnehmer();
				if($teilnehmer[0] == $username) {
					echo $teilnehmer[1];
				} else {
					echo $teilnehmer[0];
				}
				?>
			</a>
		</li>
		<?php
	}
	?>
</ul>
<?php
} else {
	?>
	<p>
		Schreiben Sie jemandem eine Nachricht, indem Sie den Link auf seinem Profil aufrufen.
	</p>
	<?php
}