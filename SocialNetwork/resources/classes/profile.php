<?php
echo "in der profilklasse";
require_once(CLASSES_PATH ."/sql.php");
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/friendship.php");
echo "in profilklasse nach requiren";
class profile
{
	protected $user;

	public function getUsername()
	{
		return $this->user->getUsername();
	}

	public function getUser()
	{
		return $this->user;
	}

	public function __construct(user $user)
	{
		$this->user = $user;
	}

	public function renderProfile()
	{
		$username = user::findUserBySid(session_id());
		if($username == $this->getUser()->getUsername()) {
			$ownProfile = true;
		} else {
			$ownProfile = false;
		}

		if($ownProfile) {
			?>
			<p>
				<a href="?page=editProfile">Profil bearbeiten</a>
			</p>
			<?php
		} else {
			$sql = "SELECT * FROM friendship WHERE 
			(freund1 = :username || freund2 = :username) && 
			(freund1 = :otherUser || freund2 = :otherUser)";
			$params = array(':username' => $username, ':otherUser' => $this->getUser()->getUsername());
			$result = sql::exe($sql, $params);
			if(sizeof($result) != 0) {
				?>
				<p>Du bist mit <?= $this->getUser()->getUsername()?> befreundet.</p>
				<p><a href="?page=newHistory&freund=<?= $this->getUser()->getUsername()?>">Sende eine Nachricht an <?= $this->getUser()->getUsername()?></a></p>
				<?php
			} else {
				?>
				<p>Willst du <?= $this->getUser()->getUsername()?> eine Freundschaftsanfrage senden?</p>
				<p>Dann klicke <a href="?page=sendFriendrequest&id=<?= $this->getUser()->getUsername()?>">hier</a></p>
				<?php
			}
		}

		?>
		<h3>
			Profil von <?= $this->getUser()->getUsername()?>
		</h3>
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