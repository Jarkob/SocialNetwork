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

		// Andernfalls soll der Freundschaftsstatus zwischen dem User und dem Profilbesitzer angezeigt werden.
		// Wenn sie nicht befreundet sind, soll die Möglichkeit gegeben werden, dem Profilbesitzer eine Freundschaftsanfrage zu schicken.
		if(!$ownProfile) {
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
				<p><a href="?page=newHistory&friend=<?= $otherUsername?>">Sende eine Nachricht an <?= $otherUsername?></a></p>
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
		<div class="row">
			<h3>
				Profil von <?= $this->getUser()->getUsername()?>
			</h3>

			<?php

			// Profilbild laden
			if(file_exists("img/content/profile/". $this->getUser()->getUsername() .".jpg")) {
				?>
				<img id="profilePicture" title="Profilbild" src="img/content/profile/<?= $this->getUser()->getUsername()?>.jpg" style="width: 300px">
				<?php
			} else if(file_exists("img/content/profile/". $this->getUser()->getUsername() .".png")) {
				?>
				<img id="profilePicture" title="Profilbild" src="img/content/profile/<?= $this->getUser()->getUsername()?>.jpg" style="width: 300px">
				<?php
			} else {
				?>
				<img id="profilePicture" title="Profilbild" src="img/content/profile/default.png" style="width: 300px">
				<?php
			}
			?>
		</div>

		<div class="row">
			<div class="col-xs-4 col-sm-4 col-md-4 col-ld-4">
				<h4>Informationen</h4>
				<ul class="list-group">
					<li class="list-group-item">
						Vorname: <?= $this->getUser()->getVorname()?>
					</li>
					<li class="list-group-item">
						Nachname: <?= $this->getUser()->getNachname()?>
					</li>
					<li class="list-group-item">
						Geburtsdatum: <?= $this->getUser()->getGebdatum()?>
					</li>
					<li class="list-group-item">
						Geschlecht: <?= $this->getUser()->getGeschlecht()?>
					</li>
					<li class="list-group-item">
						Beziehungsstatus: <?= $this->getUser()->getBezstatus()?>
					</li>
				</ul>
			
				<?php
				$friends = $this->getUser()->getFriends();
				?>
				<h4>Freunde(<?= sizeof($friends)?>)</h4>
				<ul class="list-group">
					<?php
					foreach($friends as $friend) {
						?>
						<li class="list-group-item">
							<a href="?page=profile&owner=<?= $friend?>"><?= $friend?></a>
						</li>
						<?php
					}
					?>
				</ul>
			</div>

			<div class="col-xs-8 col-sm-8 col-md-8 col-xs-8">
				<?php
				
				$sql = "SELECT * FROM entry WHERE autor = :username ORDER BY zeit DESC";
				$params = array(":username" => $this->getUser()->getUsername());
				$alleEintraege = sql::exe($sql, $params);
				$anzahlEintraege = sizeof($alleEintraege);

				?>
					<h4>Beiträge(<?= $anzahlEintraege?>)</h4>
				<?php

				$anzahlProSeite = 10;
				$anzahlSeiten = ($anzahlEintraege / $anzahlProSeite) + 1;

				if(empty($_GET['nr'])) {
					$seite = 1;
				} else {
					$seite = $_GET['nr'];
					if($seite > $anzahlSeiten) {
						$seite = 1;
					}
				}

				$start = ($seite * $anzahlProSeite) - $anzahlProSeite;
				$limit = 10;

				$sql = "SELECT * FROM entry WHERE autor = :username ORDER BY zeit DESC LIMIT ". $limit ." OFFSET ". $start;
				$params = array(":username" => $this->getUser()->getUsername());
				$result = sql::exe($sql, $params);//argh

				$entries = array();
	
				for($i = 0; $i < sizeof($result); $i++) {
					$entries[] = new entry($result[$i]['autor'], $result[$i]['content'], $result[$i]['id']);
				}

				foreach($entries as $entry) {
					$entry->renderEntry();
				}
				?>

				<ul class="pagination">
					<?php
					for($i = 1; $i <= $anzahlSeiten; $i++) {
						if($seite == $i) {
							?>
							<li>
								<a href="?page=profile&owner=<?= $this->getUser()->getUsername()?>&nr=<?= $i?>"><b>Seite <?= $i?></b></a>
							</li>
							<?php
						} else {
							?>
							<li>
								<a href="?page=profile&owner=<?=$this->getUser()->getUsername()?>&nr=<?= $i?>">Seite <?= $i?></a>
							</li>
							<?php
						}
					}
					?>
				</ul>
			</div>
		</div>
		<?php
	}
}
?>