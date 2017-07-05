<h2>Freundschaftsfragen</h2>
<?php
echo "vor requiren";
require_once(CLASSES_PATH ."/user.php");
echo "nach requiren von user";
require_once(CLASSES_PATH ."/friendrequest.php");
echo "nach requiren";
$username = user::findUserBySid(session_id());
echo "vor erhalterne";
$erhalteneFreundschaftsanfragen = friendrequest::getFriendrequestByParticipating(null, $username);//mal sehen
echo "vor gesendeee";
$gesendeteFreundschaftsanfragen = friendrequest::getFriendrequestByParticipating($username, null);//mal sehen
echo "nach initialisierung von anfragen";
if(sizeof($erhalteneFreundschaftsanfragen) != 0) {
	?>
	<h3>Erhaltene Freundschaftsanfragen</h3>
	<?php
	foreach($erhalteneFreundschaftsanfragen as $erhalteneFreundschaftsanfrage) {
		?>
		<p>
			Freundschaftsanfrage von <?= $erhalteneFreundschaftsanfrage['sender']?>
			<a href="?page=acceptFriendrequest&id=<?= $erhalteneFreundschaftsanfrage['id']?>">Akzeptieren</a>
			<a href="?page=declineFriendrequest&id=<?= $erhalteneFreundschaftsanfrage['id']?>">Ablehnen</a>
		</p>
		<?php
	}
}

if(sizeof($gesendeteFreundschaftsanfragen) != 0) {
	?>
	<h3>Gesendete Freundschaftsanfragen</h3>
	<?php
	foreach($gesendeteFreundschaftsanfragen as $gesendeteFreundschaftsanfrage) {
		?>
		<p>
			Freundschaftsanfrage an <?= $gesendeteFreundschaftsanfrage['empfaenger']?>
		</p>
		<?php
	}
}

if(sizeof($erhalteneFreundschaftsanfragen) == 0 && $gesendeteFreundschaftsanfragen == 0) {
	?>
	<p>Finden Sie Freunde über die Suchleiste in der Navigation.</p>
	<?php
}
?>