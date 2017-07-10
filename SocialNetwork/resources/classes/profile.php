<?php
require_once(CLASSES_PATH ."/sql.php");
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/friendship.php");

// Profil eines Users
class profile
{
	// Das zugehörige User Objekt
	protected $user;

	public function getUsername()
	{
		return $this->user->getUsername();
	}

	public function getUser()
	{
		return $this->user;
	}

	// Erstellt das Profil anhand des zugehörigen Userobjekts
	public function __construct(user $user)
	{
		$this->user = $user;
	}

	// Stellt das Profil auf der Seite dar
	public function renderProfile()
	{
		$username = user::findUserBySid(session_id());
		if($username == $this->getUser()->getUsername()) {
			$ownProfile = true;
		} else {
			$ownProfile = false;
		}

		// Wenn das Profil das des Users ist, dann soll er die Möglichkeit angezeigt bekommen, sein Profil zu bearbeiten.
		// Andernfalls soll der Freundschaftsstatus zwischen dem User und dem Profilbesitzer angezeigt werden.
		// Wenn sie nicht befreundet sind, soll die Möglichkeit gegeben werden, dem Profilbesitzer eine Freundschaftsanfrage zu schicken.
		if($ownProfile) {
			?>
			<p>
				<a href="?page=editProfile">Profil bearbeiten</a>
			</p>
			<?php
		} else {
			// Prüfen ob User und Profilbesitzer befreundet sind
			$otherUsername = $this->getUser()->getUsername();
			$sql = "SELECT * FROM friendship WHERE 
			(freund1 = :username || freund2 = :username) && 
			(freund1 = :otherUser || freund2 = :otherUser)";
			$params = array(':username' => $username, ':otherUser' => $otherUsername);
			$result = sql::exe($sql, $params);

			// Wenn Sie befreundet sind wird die Möglichkeit geboten, dem Profilbesitzer eine Nachricht zu senden.
			if(sizeof($result) != 0) {
				?>
				<p>Du bist mit <?= $this->getUser()->getUsername()?> befreundet.</p>
				<p><a href="?page=newHistory&freund=<?= $otherUsername?>">Sende eine Nachricht an <?= $otherUsername?></a></p>
				<?php
			} else {
				?>
				<p>Willst du <?= $otherUsername?> eine Freundschaftsanfrage senden?</p>
				<p>Dann klicke <a href="?page=sendFriendrequest&id=<?= $otherUsername?>">hier</a></p>
				<?php
			}
		}

		// Anzeigen der Profildaten
		?>
		<h3>
			Profil von <?= $this->getUser()->getUsername()?>
		</h3>

		<?php
		// Profilbild laden
		if(file_exists("img/content/profile/". $this->getUser()->getUsername() .".jpg")) {
			?>
			<img id="profilePicture" title="Profilbild" src="img/content/profile/<?= $otherUsername?>.jpg" style="width: 300px">
			<?php
		} else if(file_exists("img/content/profile/". $otherUsername .".png")) {
			?>
			<img id="profilePicture" title="Profilbild" src="img/content/profile/<?= $otherUsername?>.jpg" style="width: 300px">
			<?php
		} else {
			?>
			<img id="profilePicture" title="Profilbild" src="img/content/profile/default.png" style="width: 300px">
			<?php
		}
		?>

		<p>
			Vorname: <?= $this->getUser()->getVorname()?>
		</p>
		<p>
			Nachname: <?= $this->getUser()->getNachname()?>
		</p>
		<p>
			Geburtsdatum: <?= $this->getUser()->getGebdatum()?>
		</p>
		<?php
	}
}
?>