<?php
echo "in der profilklasse";
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
				<p>Du bist mit <?= $id?> befreundet.</p>
				<p><a href="?page=newHistory&freund=<?= $id?>">Sende eine Nachricht an <?= $id?></a></p>
				<?php
			} else {
				?>
				<p>Willst du <?= $id?> eine Freundschaftsanfrage senden?</p>
				<p>Dann klicke <a href="?page=sendFriendrequest&id=<?= $id?>">hier</a></p>
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